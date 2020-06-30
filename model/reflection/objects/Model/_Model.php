<?php
/**
 * ADVERTENCIA : UN OBJETO MODELO NECESITA:
 * 1) CREARSE
 * 2) LLAMAR A INITIALIZE()
 * 3) LLAMAR A o()
 * Para evitar relaciones circulares.
 *
 * @author Jose (13/10/2012)
 */
namespace model\reflection;
include_once(PROJECTPATH."/model/reflection/objects/Permissions/ModelPermissionsDefinition.php");
include_once(PROJECTPATH."/model/reflection/objects/Model/objects/Alias/AliasFactory.php");

use \model\reflection\Model\ModelName;
class Model extends \model\reflection\base\SimpleModelDefinition
{
    var $aliases=array();
    var $actions=array();
    var $datasources=array();
    var $package;
    var $actionsLoaded=false;
    var $datasourcesLoaded=false;
    var $extendedModel="";
    var $typeField=null;
    var $subTypes;
    var $storageConfigs;
    var $pages;
    var $aliasesLoaded=false;
    var $modelDescriptor;
    var $serializer;
    var $modelService;
    function __construct($modelDescriptor,$layer=null)
    {
        $this->modelService=\Registry::getService("model");
        $this->modelDescriptor=$this->modelService->getModelDescriptor($modelDescriptor);
        try{
           $this->baseDir=$this->modelDescriptor->getDestinationFile();
           $this->definition=$this->modelDescriptor->getDefinition();
           $this->package=$this->modelDescriptor->package;
           parent::__construct(
                          "Definition",
                           $this->package,
                           $this->modelDescriptor->getNamespaced(),
                           $this->modelDescriptor->getPath("Definition.php")
                          );
      }
      catch(\lib\model\types\BaseTypeException $e)
      {
          $this->hasDefinition=false;
      }
     // $this->config=new \model\reflection\base\ModelConfiguration($this);
    }

    function initialize($definition=null)
    {
        parent::initialize();
        $this->loadModelPermissions();
        if(isset($this->definition["EXTENDS"]))
        {
            $this->extendedModel=$this->definition["EXTENDS"];

        }
        $this->acls=io($this->definition,"DEFAULT_PERMISSIONS",null);

        if(isset($this->definition["SERIALIZER"]))
            $this->serializer=\model\reflection\Serializers\SerializerReflectionFactory::getReflectionSerializer($this->definition["SERIALIZER"]);
        else
            $this->serializer=null;

        if(isset($this->definition["DEFAULT_SERIALIZER"]))
        {
            // TODO! WARNING! Siempre devuelve el serializador de lectura!!
            global $Config;
            $this->serializer=$Config["SERIALIZERS"][$this->definition["DEFAULT_SERIALIZER"]];


        }
        if(isset($this->definition["STATES"]))
        {

            $stateField=$this->getStateField();
            if($stateField)
            {
                $keys=array_keys($stateField);
                $sfield=$stateField[$keys[0]];
                $states=$this->definition["STATES"]["STATES"];
                $sfield->setStates($states);
            }
        }
        if(isset($this->definition["TYPEFIELD"]))
        {
            $this->typeField=$this->definition["TYPEFIELD"];
            $this->subTypes=$this->definition["FIELDS"][$this->typeField]["VALUES"];
        }
        // Se aniade una cardinalidad, en caso de que no exista.
        if(!isset($this->definition["CARDINALITY"]))
            $this->definition["CARDINALITY"]=100;

        if(isset($this->definition["STORAGE"]))
        {
            foreach($this->definition["STORAGE"] as $key=>$value)
               $this->addStorageConfiguration($key,$value);
        }
    }
    /*
     * 'DEFAULT_SERIALIZER'=>"default",
               'DEFAULT_WRITE_SERIALIZER'=>"default",
     */
    function initializeAliases()
    {
        $this->aliasesLoaded=true;
        $this->loadAliases();
    }
    function getConfiguration()
    {
        return $this->config;
    }
    static function getObjects($layer)
    {
        return \model\reflection\base\BaseDefinition::loadFilesFrom(PROJECTPATH."/".$layer."/objects/",null,false,true);
    }

    function getField($fieldName)
    {
        $inst=parent::getField($fieldName);
        if($inst)
            return $inst;

        if($this->extendedModel)
        {
            $ext=\model\reflection\ReflectorFactory::getModel($this->extendedModel);
            return $ext->getField($fieldName);
        }
    }
    function getExtendedModelName()
    {
        return $this->extendedModel;

    }
    function getModelDescriptor()
    {
        return $this->modelDescriptor;
    }
    function getClassName()
    {
        return $this->modelDescriptor->getNormalizedName();
    }
    function getNamespaceClassName()
    {
        return $this->modelDescriptor->getNamespaceModel();
    }
    // Retorna solo la parte final del path.
    function getShortName()
    {
        return $this->modelDescriptor->getClassName();
    }
    // Funcion que , en caso de que se pase un fieldName con un path ("a/b/c"), en vez de devolver a, devuelve c.
    function resolveField($fieldName)
    {
        $parts=explode("/",$fieldName);
        if($parts[0]=="") {
            array_shift($parts);
        }
        $nParts=count($parts);
        $first=$this->getFieldOrAlias($fieldName);
        if($nParts==1)
        {
            return array("model"=>$this,"field"=>$first);
        }
        array_shift($parts);
        $newPath=implode("/",$parts);
        if($first->isRelation() || $first->isAlias())
        {
            $m=$first->getRemoteModel();
            $rdef=$first->getDefinition();
            $rFields=$rdef["FIELDS"];
            return $m->resolveField($rFields[$newPath]);
        }
        return array("model"=>$this,"field"=>$first);

    }
    function getAlias($fieldName)
    {
        // Hay que tratar el caso de que existe un path dentro del nombre del campo.
        $parts=explode("/",$fieldName);
        $extraName=null;
        $origField=$fieldName;
        if(count($parts)>1)
        {

            $fieldName=$parts[0];
            array_splice($parts,0,1);
            $extraName=implode("/",$parts);

        }



        if(!$this->aliasesLoaded)
            $this->loadAliases();
        $inst=$this->aliases[$fieldName];
        if($inst)
        {
            if($extraName==null)
                return $inst;
            $def=$this->aliases[$fieldName]->getDefinition();
            $ext=\model\reflection\ReflectorFactory::getModel($def["MODEL"]);
            return $ext->getFieldOrAlias($extraName);

        }
        if($this->extendedModel)
        {
            $ext=\model\reflection\ReflectorFactory::getModel($this->extendedModel);
            return $ext->getFieldOrAlias($origField);
        }
        return null;
    }
    function hasCustomSerializer()
    {
        return $this->serializer!=null;
    }

    function getCustomSerializer()
    {
        return $this->serializer;
    }

    function isConcrete()
    {
        return true;
    }
    function getCardinality()
    {
        return $this->definition["CARDINALITY"];
    }
    function getShortLabel()
    {
       $def=$this->getDefinition();
       return $def["SHORTLABEL"];
    }

    function getExtendedModel()
    {
       return $this->extendedModel;
    }

    function getRelations()
    {
        $rels=array();
        foreach($this->fields as $key=>$value)
        {
            if($value->isRelation())
                $rels[$key]=$value;
        }
        foreach($this->aliases as $key=>$value)
        {
            if($value->isRelation())
                $rels[$key]=$value;
        }
        return $rels;
    }
    function getSimpleRelations()
    {
        $rels=array();
        foreach($this->fields as $key=>$value)
        {
            if($value->isRelation())
                $rels[$key]=$value;
        }
        return $rels;
    }

    function getTableName()
    {
        if(isset($this->definition["TABLE"]))
			return $this->definition["TABLE"];
        if($this->modelDescriptor->isPrivate())
        {
            $parentObj=$this->modelDescriptor->getNamespaceModel();
            $model=\model\reflection\ReflectorFactory::getModel($parentObj);
            $modelTable=$model->getTableName();
            $this->definition["TABLE"]=$modelTable."_".$this->modelDescriptor->className;
            return $this->definition["TABLE"];
        }
		return str_replace('\\','_',$this->modelDescriptor);
    }
    function getIndexFields()
    {
        //if(!$this->definition["EXTENDS"])
            return parent::getIndexFields();
        //$extModelInstance=\model\reflection\ReflectorFactory::getModel($this->extendedModel);
        //return $extModelInstance->getIndexFields();
    }

    function getOwnershipField()
    {
       return io($this->definition,"OWNERSHIP",null);
    }

    function loadAliases()
    {
       $this->aliases=array();
       if(!isset($this->definition["ALIASES"]))return;
       // Codigo para eliminar aliases duplicados.
       $existingAliases=array();
       foreach($this->definition["ALIASES"] as $key=>$value)
       {
           $this->aliases[$key]=\model\reflection\Model\Alias\AliasFactory::getAlias($this,$key,$value);
       }

    }
    function getAliases()
    {
        return $this->aliases;
    }

    function addAlias($name,$alias)
    {
        echo "Aniadiendo el alias $name al objeto ".$this->getNamespaced()."<br>";
        $this->aliases[$name]=$alias;
    }

    function getFieldOrAlias($name)
    {
        $field=$this->getField($name);
        if(isset($field))
            return $field;
        return $this->getAlias($name);
    }
    function getRole()
    {
        $def=$this->getDefinition();
        return $this->definition["ROLE"];
    }
    function addStorageConfiguration($storageEngine,$configuration)
    {
        $this->storageConfigs[$storageEngine]=$configuration;
    }
    function getStorageConfiguration($storageEngine)
    {
        if(!isset($this->storageConfigs[$storageEngine]))
            return NULL;
        return $this->storageConfigs[$storageEngine];
    }

    /**
     *    Function : getDefinition()
       */

        function getDefinition()
        {
            $def=array();
            if($this->extendedModel)
                $def["EXTENDS"]=$this->extendedModel;
            // LOS ROLES POSIBLES VAN A SER ENTITY / MULTIPLE_RELATION / PROPERTY
            if(!isset($this->definition["ROLE"]))
                $def["ROLE"]="ENTITY";
            else
                $def["ROLE"]=$this->definition["ROLE"];

            if(isset($this->definition['DEFAULT_SERIALIZER']))
                $def['DEFAULT_SERIALIZER']=$this->definition['DEFAULT_SERIALIZER'];
            if(isset($this->definition['DEFAULT_WRITE_SERIALIZER']))
                $def['DEFAULT_WRITE_SERIALIZER']=$this->definition['DEFAULT_WRITE_SERIALIZER'];
            // MULTIPLE_RELATION debe tener los campos "MODELS" y "OWNER"
            // MODELS es la lista de modelos que estan relacionados.
            // OWNER indica la entidad duenia de la relacion.

            if($def["ROLE"]=="MULTIPLE_RELATION")
            {
                $def["MULTIPLE_RELATION"]=$this->definition["MULTIPLE_RELATION"];
            }

            // CAMPOS INDICES USADOS POR ESTE OBJETO.Es un array de campos.
            if($this->indexFields)
                $def["INDEXFIELDS"]=$this->indexFields;
            if($this->typeField)
                $def["TYPEFIELD"]=$this->typeField;

            // Sobreescribe el nombre de la tabla a generar para este objeto.
            $name=$this->modelDescriptor->getNormalizedName();
            if(isset($this->definition["TABLE"]))
                $def["TABLE"]=$this->definition["TABLE"];
            else
                $def["TABLE"]=str_replace('\\','_',$name);

            // Etiqueta general para el objeto
            $def["LABEL"]=io($this->definition,"LABEL",$name);
            $def["SHORTLABEL"]=io($this->definition,"SHORTLABEL",$name);

            // CARDINALITY es una estimacion del numero de filas de este objeto.Obviamente, no es exacta.Sirve para
            // "tener una idea" de si esta tabla va a ser muy grande, o no.
            $def["CARDINALITY"]=io($this->definition,"CARDINALITY",100);

            // CARDINALITY_TYPE indica si el numero de filas de este objeto va a variar mucho o no.De esta forma, sabemos si
            // una tabla que indica 20 en su CARDINALITY,y su CARDINALITY_TYPE es FIXED, es mejor no cargarla LAZY, define el tipo de input a generar por defecto, etc.
            $def["CARDINALITY_TYPE"]=io($this->definition,"CARDINALITY_TYPE","VARIABLE");

             $firstStringField="";
             foreach($this->fields as $key=>$value)
             {
                $def["FIELDS"][$key]=$value->getDefinition();
                if($firstStringField=="" && $def["FIELDS"][$key]["TYPE"]=='String')
                    $firstStringField=$key;
             }
             foreach($this->aliases as $key=>$value)
             {
                 $def["ALIASES"][$key]=$value->getDefinition();
             }
            // if($this->serializer)
            //     $def["SERIALIZER"]=$this->serializer->getDefinition();


             if($this->modelPermissions)
                 $def["PERMISSIONS"]=$this->modelPermissions->getDefinition();

             $owner=$this->getOwnershipField();

             if($owner)
             {
                     $def["OWNERSHIP"]=$owner;
             }

             if($this->acls)
             {
                 $def["DEFAULT_PERMISSIONS"]=$this->acls;
             }
             if(isset($this->definition["STATES"]))
                 $def["STATES"]=$this->definition["STATES"];

             if(isset($this->storageConfigs))
             {
                 foreach($this->storageConfigs as $key=>$value)
                 {
                     $def["STORAGE"][$key]=$value;
                 }
             }
             return $def;
        }

        function addAction($name,$actionObj)
        {
            $this->actions[$name]=$actionObj;
        }

        function save($all=true)
        {
            $this->saveDefinition();

            if($all)
            {
                foreach($this->actions as $key=>$value)
                {
                     $value->save($key);
                }
                foreach($this->datasources as $key=>$value)
                    $value->save($key);
            }
        }

        function saveDefinition()
        {
            $def=$this->getDefinition();
            $this->addProperty(array("NAME"=>"definition",
                                     "ACCESS"=>"static",
                                     "DEFAULT"=>$def));
            $this->generate();

        }

        function addDataSource($dataSource)
        {
            $this->datasources[$dataSource->getName()]=$dataSource;
        }

        function getSerializer()
        {

            if($this->hasCustomSerializer())
                {
                    $serDef=$this->getCustomSerializer();
                    $ser=\lib\storage\StorageFactory::getSerializer($serDef);
                    return $ser;
                    // TODO : Hacer que utilice su custom namespace
                    $curSerializer->useDataSpace($serDef["database"]);

                }
                else
                {
                    $layer=\model\reflection\ReflectorFactory::getLayer($this->modelDescriptor->layer);
                    return $layer->getSerializer();
                }
        }


        function getDataSources()
        {
            if(!$this->datasourcesLoaded)
                $this->loadDataSources();
            return $this->datasources;
        }
        function getDataSource($name)
        {
            if(!$this->datasourcesLoaded)
                $this->loadDataSources();

            return io($this->datasources,$name,null);
        }


        function loadDataSources()
        {
            if($this->datasourcesLoaded)
            {
                return $this->datasources;
            }
            $dsnames=$this->loadFilesFrom($this->modelDescriptor->getPath("/datasources/"),"/.*\.php/",true,false,true);
            foreach($dsnames as $curDs)
            {
                include_once($this->modelDescriptor->getPath('/datasources/'.$curDs.".php"));
                $dsclass=$this->modelDescriptor->getNamespaced().'\\datasources\\'.$curDs;
                $instance=new $dsclass();
                if(is_a($instance,'\lib\datasource\MultipleDataSource'))
                    continue;
                $this->datasources[$curDs]=new \model\reflection\DataSource($curDs,$this);
                $this->datasources[$curDs]->initialize();
            }
            $this->datasourcesLoaded=true;
            return $this->datasources;
        }

        function loadModelPermissions()
        {
                $this->modelPermissions=new \model\reflection\Permissions\ModelPermissionsDefinition($this,$this->definition["PERMISSIONS"]?$this->definition["PERMISSIONS"]:array());
        }
        function getPermissionsDefinition()
        {
            return $this->modelPermissions;
        }

        function getActions()
        {
            if(!$this->actions) {
                $this->loadActions();
            }
            return $this->actions;
        }
        function getAction($name)
        {
            if(!$this->actions)
                $this->loadActions();
            return $this->actions[$name];
        }
        function loadActions()
        {
            $this->actions=array();
            $actions=$this->loadFilesFrom($this->modelDescriptor->getPath('')."/actions/","/.*\.php/",true,false,true);
            foreach($actions as $curAct)
            {
                $this->actions[$curAct]=new \model\reflection\Action($curAct,$this);
                $this->actions[$curAct]->initialize();
            }
        }

        function getInvRelationships()
        {
            $results=array();
            foreach($this->aliases as $key=>$value)
            {
                if(is_a($value,'\model\reflection\Model\Alias\InverseRelation'))
                    $results[$key]=$value;
            }
            return $results;
        }
        function getLabelFields()
        {
            $results=array();
            foreach($this->fields as $key=>$value)
            {
                if($value->isLabel())
                    $results[$key]=$value;
            }
            return $results;
        }
    function getSearchableFields()
    {
        $results=array();
        foreach($this->fields as $key=>$value)
        {
            if($value->isSearchable())
                $results[$key]=$value;
        }
        return $results;
    }

        function getDescriptiveFields()
        {
            $results=array();
            foreach($this->fields as $key=>$value)
            {
                if($value->isDescriptive())
                    $results[$key]=$value;
            }
            return $results;
        }
        function getRelationFields()
        {
           return $this->getRelations();
        }
        function getStateField()
        {
            foreach($this->fields as $key=>$value)
            {
                if($value->isState())
                    return array($key=>$value);
            }
            return null;
        }
        function getDefaultState()
        {
            $stateField=$this->getStateField();
            if(!$stateField)
                return null;
            $vals=array_values($stateField);
            $types=array_values($vals[0]);
            return $types[0]->getDefaultState();

        }

        function saveInitialData()
        {

        }

        function runSetup()
        {
            $layer=$this->modelDescriptor->layer;
            $objName=$this->modelDescriptor->getNormalizedName();
            $sPath=$this->modelDescriptor->getPath("Setup.php");
              if(is_file($sPath))
              {
                  include_once($sPath);
                  $className=$this->modelDescriptor->getNamespaced().'\\Setup';
                  if(class_exists($className))
                  {
                      $setupInstance= new $className();
                      if(method_exists($setupInstance,"install"))
                           $setupInstance->install();
                   }
               }
        }

        function createDerivedRelations()
        {
            $relations=$this->getRelations();
            echo "<h3>Generando relaciones derivadas para ".$this->modelDescriptor->getNamespaced()."</h3>";
            foreach($relations as $relName=>$relObject)
            {
                echo "Analizando relacion $relName<br>";
                $relObject->createDerivedRelation();
            }
        }

        function hasRelationWith($model,$fields)
        {
            $relations=$this->getRelations();
            foreach($relations as $key=>$value)
            {
                if($value->pointsTo($model,$fields))
                    return true;
            }
            return false;
        }
        function areIndexesContained($fieldList)
        {
            $indexf=$this->getIndexFields();
            $keys=array_keys($indexf);

            if(count($keys)==count(array_intersect($keys,$fieldList)))
                return $keys;
            return null;
        }
        function getSubTypes()
        {
            return $this->subTypes;
        }
        function getSubTypeField()
        {
            return $this->typeField;
        }
        function addDataSourcePage($page,$ds)
        {
            $this->pages["DATASOURCES"][]=array("page"=>$page,"datasource"=>$ds);
        }
        function getDataSourcePages()
        {
            return $this->pages["DATASOURCES"];
        }
        function addActionPage($page,$action)
        {
            $this->pages["ACTIONS"][]=array("page"=>$page,"action"=>$action);
        }
        function getActionPages()
        {
            return $this->pages["ACTIONS"];
        }
        static function getMetaData($modelDescriptor)
        {
            include_once(__DIR__."/ModelMetadata.php");
            return new \model\reflection\Model\ModelMetadata($modelDescriptor);
        }

}

