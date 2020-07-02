<?php
namespace lib\datasource;
include_once(LIBPATH."/datasource/DataSource.php");
include_once(LIBPATH."/datasource/DataSourceFactory.php");
abstract class StorageDataSource extends TableDataSource
{
    protected $serializerDefinition;
    protected $parameters;
    protected $parentDs;
    protected $joinBy;
    protected $joinType;
    protected $childDataSources;
    protected $serializer;
    protected $serializerType;

    protected $enabledFilters=array();
    protected $iterator=null;
    protected $mapField=null;
    protected $parentField=null;
    protected $data;

    protected $__loaded;
    protected $grouped=false;
    function __construct($objName,$dsName,$definition,$serializer)
    {

        parent::__construct($objName,$dsName,$definition);
        // Se necesita el serializador propio para el objeto que nos han pasado, y no el serializador que
        // hemos recibido como parametro, ya que el objeto padre podria ser "web", y esta accediendo a un
        // datasource de un objeto de tipo "app".
        if(!$serializer)
        {
            $this->serializer=$this->getSerializer();
        }
        else
            $this->serializer=$serializer;
        $this->serializerType=$this->serializer->getSerializerType();
        //if(\Registry::$registry["currentPage"])
        //    $this->setParameters(\Registry::$registry["currentPage"]);
    }

    function initialize(& $parentDs=null,$joinBy=null,$parentField=null,$joinType=null)
    {
        $this->parentDs=$parentDs;
        //$this->joinBy=$joinBy;
        if($joinBy!=null)
        {
            $k=array_keys($joinBy);
            $this->mapField=$k[0];
            if(isset($this->__objectDef["INDEXFIELDS"][$parentField]["MAPS_TO"]))
                $this->mapField=$this->__objectDef["INDEXFIELDS"][$parentField]["MAPS_TO"];
            $this->parentField=$parentField;
        }
        $this->joinType=$joinType;
        //$this->validate();
    }

    function addConditions($conds)
    {
        $conds=array_map(function($it){return array("FILTER"=>$it);},$conds);
        if(!$this->serializerDefinition["DEFINITION"]["CONDITIONS"])
            $this->serializerDefinition["DEFINITION"]["CONDITIONS"]=$conds;
        else
            $this->serializerDefinition["DEFINITION"]["CONDITIONS"]=array_merge($this->serializerDefinition["DEFINITION"]["CONDITIONS"],$conds);
    }
    function getStartingRow()
    {
        $f=$this->pagingParameters->__getField("__start");
        if($f->__hasOwnValue())
            return $f->getValue();
        return 0;
    }
    function getOriginalDefinition()
    {
        return $this->originalDefinition;
    }
    function getPagingParameters()
    {
        return $this->pagingParameters;
    }


    function getSerializer()
    {
        if($this->serializer)
            return $this->serializer;

        $service=\Registry::getService("storage");
        if(isset($this->__objectDef["SERIALIZER"]))
        {
            if(is_array($this->__objectDef["SERIALIZER"]))
                $this->serializer=$service->getSerializer($this->__objectDef["SERIALIZER"]);
            else
                $this->serializer=$service->getSerializerByName($this->__objectDef["SERIALIZER"]);
        }
        else
            $this->serializer= $service->getDefaultSerializer($this->objName);
        return $this->serializer;
    }


    function fetchAll()
    {
        if($this->pagingParameters->{"*__accumulated"}->__hasOwnValue() || $this->pagingParameters->{"*__group"}->__hasOwnValue())
        {
            $group=$this->pagingParameters->__group;
            if(!$group)
                return $this->fetchGrouped();
            return $this->fetchGrouped($group,$this->pagingParameters->__groupParam);
        }
        return $this->doFetch();
    }
    function getBuiltQuery($getRows=true)
    {
        return $this->serializer->buildQuery($this->serializerDefinition["DEFINITION"], $this->parameters?$this->parameters:$this, $this->pagingParameters,$getRows);
    }

    function doFetch()
    {
        // Chequear aqui la cache.
        if($this->isLoaded())
            return;
        $this->serializer->fetchAll($this->serializerDefinition["DEFINITION"],$this->data,$this->nRows,$this->matchingRows,$this->parameters?$this->parameters:$this,$this->pagingParameters);
        $this->matchingRows=intval($this->matchingRows);
        $this->iterator=new \lib\model\types\DataSet(array("FIELDS"=>$this->__returnedFields),$this->data,$this->nRows, $this->matchingRows,$this,$this->mapField);
        $this->__loaded=true;
        return $this->iterator;
    }

    function fetchCursor()
    {
        // Chequear aqui la cache.
        if($this->isLoaded())
            return;
        $this->__loaded=true;
        return $this->serializer->fetchCursor($this->serializerDefinition["DEFINITION"],$this->data,$this->nRows,$this->matchingRows,$this->parameters?$this->parameters:$this,$this->pagingParameters);
    }
    function next()
    {
        return $this->serializer->next();
    }
    function setEmpty()
    {
        $this->__loaded=true;
        $this->iterator=new \lib\model\types\DataSet(array("FIELDS"=>$this->__returnedFields),array(),0, 0,$this,$this->mapField);
    }

    function getSubDataSources()
    {
        if(isset($this->__objectDef["INCLUDE"]))
        {
            return array_keys($this->__objectDef["INCLUDE"]);
        }
    }
    function getSubDataSourceInstance($name)
    {
        $include=$this->__objectDef["INCLUDE"][$name];
        if(!$include)
        {
            throw new DataSourceException(DataSourceException::ERR_UNKNOWN_CHILD_DATASOURCE,array("object"=>$this->objName,
                "ds"=>$this->dsName,
                "subDs"=>$name));
        }
        return DataSourceFactory::getDataSource($include["MODEL"],$include["DATASOURCE"]);
    }
    function getSubDataSource($name)
    {
        if($this->grouped)
            throw new \lib\datasource\DataSourceException(\lib\datasource\DataSourceException::ERR_DATASOURCE_IS_GROUPED);
        $include=$this->__objectDef["INCLUDE"][$name];
        $subDs=$this->getSubDataSourceInstance($name);

        // TODO : El siguiente codigo solo permite 1 campo JOIN.Se nota en que la variable parentField, que es la que se le va a
        // pasar al subdatasource, es sobreescrita por cada paso por el bucle.
        // Tambien hay que fijarse en que los campos por los que se hace join, son columnas, pero sus valores se estan asignando a
        // parametros del datasource hijo, por lo que los nombres deben coincidir.

        $nJoins=0;
        foreach($include["JOIN"] as $key=>$value)
        {
            $field=$subDs->__getField($key);
            $parentField=$value;
            $arrayType=array("TYPE"=>"Array","ELEMENTS"=>$field->getDefinition());
            $newField=$subDs->__addField($key,$arrayType);
            $val=$this->iterator->getColumn($value);
            if(!(count($val)==1 && $val[0]==null))
            {
                $newField->apply($val,\lib\model\types\BaseType::VALIDATION_MODE_NONE);
                $nJoins++;
            }
        }

        $subDs->initialize($this,$include["JOIN"],$parentField,$include["JOINTYPE"]);
        if($nJoins == 0)
        {
            $subDs->setEmpty();
        }
        if(isset($include["CONDITIONS"]))
        {
            $subDs->addConditions($include["CONDITIONS"]);
        }
        if(isset($include["SORT"]))
        {
            $subDs->sortBy($include["SORT"]["FIELD"],isset($include["SORT"]["DIRECTION"])?$include["SORT"]["DIRECTION"]:'ASC');
        }
        return $subDs;
    }
    // Se sobreescribe este metodo de BaseTypedObject
    function isFieldRequired($fieldName)
    {
        $fieldDef=$this->__getField($fieldName)->getDefinition();
        return isset($fieldDef["REQUIRED"])?$fieldDef["REQUIRED"]:false;
    }
    function __set($varName,$value)
    {
        try
        {
            parent::__set($varName,$value);
        }catch(\Exception $e)
        {
            $this->pagingParameters->{$varName}=$value;
        }
    }
    function sortBy($sortField,$sortDirection="ASC")
    {
        $this->pagingParameters->__sort=$sortField;
        $this->pagingParameters->__sortDir=$sortDirection;
    }

}

?>
