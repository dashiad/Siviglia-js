<?php
namespace lib\datasource;
class DataSourceException extends \lib\model\BaseException
{
    const ERR_DATASOURCE_IS_GROUPED=5;
    const ERR_NO_SUCH_DATASOURCE=1;
    const ERR_INVALID_DATASOURCE_PARAM=2;
    const ERR_PARAM_REQUIRED=3;
    const ERR_UNKNOWN_CHILD_DATASOURCE=4;
    const ERR_NO_MODEL_OR_METHOD=5;
}
abstract class DataSource extends \lib\model\BaseTypedObject
{

    protected $isLoaded=false;
    protected $objName;
    protected $dsName;
    protected $pagingParameters=null;
    //protected $paramsModel;
    protected $originalDefinition=null;
    protected $__returnedFields;

    abstract function fetchAll();
    abstract function getIterator($rowInfo=null);
    function __construct($objName,$dsName,$definition)
    {
        // Se hace un merge de indices y params.Esto deberia ser cambiado.
        // TODO: hacer que la generacion de codigo genere INDEXFIELDS como solo nombres de campo, estando la definicion en PARAMS.
        $this->originalDefinition=$definition;
        $localFields=isset($definition["PARAMS"])?$definition["PARAMS"]:array();

        $pagingParams=array(
            "__start"=>array("TYPE"=>"Integer"),
            "__count"=>array("TYPE"=>"Integer"),
            "__sort"=>array("TYPE"=>"String"),
            "__sortDir"=>array("TYPE"=>"Enum","VALUES"=>array("ASC","DESC"),"DEFAULT"=>"ASC"),
            "__sort1"=>array("TYPE"=>"String"),
            "__sortDir1"=>array("TYPE"=>"Enum","VALUES"=>array("ASC","DESC"),"DEFAULT"=>"ASC"),
            "__group"=>array("TYPE"=>"String","MAXLENGTH"=>30),
            "__groupParam"=>array("TYPE"=>"String","MAXLENGTH"=>30),
            "__groupMin"=>array("TYPE"=>"String","MAXLENGTH"=>30),
            "__groupMax"=>array("TYPE"=>"String","MAXLENGTH"=>30),
            "__accumulated"=>array("TYPE"=>"Boolean"),
            "__partialAccumul"=>array("TYPE"=>"Boolean"),
            "__autoInclude"=>array("TYPE"=>"String")

        );
        if(!isset($this->originalDefinition["PARAMS"]))
            $this->originalDefinition["PARAMS"]=$pagingParams;
        else
            $this->originalDefinition["PARAMS"]=array_merge_recursive($this->originalDefinition["PARAMS"],$pagingParams);

        // Se hace que, si no hay un valor por defecto definido en los parametros, el valor por defecto sea NULL.
        // Esto sirve para eliminar el siguiente problema:
        // Cuando un parametro de un datasource se define como una referencia a un campo de un modelo, lo que quiere heredar
        // es la definicion de tipo, SIN el valor por defecto.El valor por defecto del tipo se refiere al valor que tiene
        // cuando se crea una nueva instancia del mismo, y no coincide con el valor por defecto que queremos en un datasource.
        // Por ello, TipeFactory debe sobreescribir el "DEFAULT" base declarado en el campo del modelo, por el "DEFAULT" declarado
        // en el parametro del datasource que apunta a ese campo.
        foreach($this->originalDefinition["PARAMS"] as $key=>& $value)
        {
            if(!isset($value["DEFAULT"]))
                $value["DEFAULT"]=null;
        }

        $this->pagingParameters=new \lib\model\BaseTypedObject(array(
            "FIELDS"=>$pagingParams
        ));
        // Lo que son los parametros, pasan a ser los fields de este campo.
        // Y lo que son los FIELDS, lo guardamos en $returnedFields, para su uso con los resultados de la query.
        $this->__loaded=false;
        $this->__returnedFields=$definition["FIELDS"];
        $definition["FIELDS"]=$localFields;
        parent::__construct($definition);
        $this->objName=$objName;
        $this->dsName=$dsName;
    }
    function getParametersInstance()
    {
        return new \lib\model\BaseTypedObject(["FIELDS"=>$this->originalDefinition["PARAMS"]]);
    }
    function setParameters($obj)
    {

        $remFields=$obj->__getFields();
        $pagingFields=$this->pagingParameters->__getFields();
        $pagingKeys=array_keys($pagingFields);
        if(!$remFields)
            return;
        foreach($remFields as $key=>$value)
        {
            if(in_array($key,$pagingKeys))
            {
                $types=$value->getTypes();

                foreach($types as $tKey=>$tValue)
                {
                    $this->pagingParameters->{"*".$key}->copy($tValue);
                }
                continue;
            }
            if(!isset($this->__fieldDef[$key]))
                continue;
            $types=$value->getTypes();
            foreach($types as $tKey=>$tValue)
            {
                try{
                    $field=$this->__getField($tKey);
                    $field->copy($tValue);
                }
                catch(\lib\model\BaseTypedException $e)
                {
                    if($e->getCode()==\lib\model\BaseTypedException::ERR_NOT_A_FIELD)
                    {
                        // El campo no existe.No se copia, pero se continua.
                        continue;
                    } // En cualquier otro caso, excepcion.
                    else
                        throw $e;
                }
            }
        }
    }

    public function getOriginalDefinition()
    {
        return $this->originalDefinition;
    }

    function isLoaded()
    {
        return $this->isLoaded;
    }
    function getFieldsKey()
    {
        return "PARAMS";
    }

}

abstract class TableDataSource extends DataSource {
    abstract function count();
    abstract function countColumns();
    abstract function getMetaData();
}


?>
