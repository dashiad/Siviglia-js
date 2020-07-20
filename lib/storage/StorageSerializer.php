<?php
namespace lib\storage;
use lib\model\types\TypeFactory;
use lib\storage\ES\ESSerializerException;
use lib\storage\ES\QueryBuilder;
include_once(__DIR__."/TypeSerializer.php");
class StorageSerializerException extends \lib\storage\TypeSerializerException
{

    const ERR_NO_ID_FOR_OBJECT = 2;
    const TXT_NO_ID_FOR_OBJECT="No se ha recibido un identificador para el objeto";
    const ERR_NO_SUCH_OBJECT = 3;
    const TXT_NO_SUCH_OBJECT = "No se encuentra el objeto con identificador [%id%]";
    const ERR_NO_CONNECTION_DETAILS=4;
    const TXT_NO_CONNECTION_DETAILS="No se han proporcionado detalles de conexion";
}
abstract class StorageSerializer extends TypeSerializer
{
    protected $storageManager;

    protected $definition;
    protected $innerDefinition;

    function getStorageManager()
    {
        return $this->storageManager;
    }



	function serialize($object)
	{
		$object->setSerializer($this);
		$object->save();
	}

    function getName()
    {
        return $this->name;
    }
    function getConfig()
    {
        return $this->definition;
    }

    function getIndexExpression($object)
    {
        $keys = $object->__getKeys();
        if (!$keys)
            return null;

        $fields = $keys->serialize($this);

        $expr = "";

        foreach ($fields as $key => $value)
        {
            $conditions[] = array(
                "FILTER" => array(
                    "F" => $key,
                    "V" => $value,
                    "OP" => "="
                )
            );
        }
        return $conditions;
    }
    function _batchStore($objectArray)
    {
        for($k=0;$k<count($objectArray);$k++)
        {
            $object=$objectArray[$k];
            $isNew=$object->__isNew();
            $this->_store($object,$isNew,null);

        }
    }
    function _store($object, $isNew, $dirtyFields=null)
    {
        $results = array();

        $newDirty=[];
        foreach($dirtyFields as $k=>$v)
        {
            $parts=explode("/",$k);
            $f="";
            if(count($parts)>1)
            {
                if($parts[0]=="")
                    $f=$parts[1];

                else
                    $f=$parts[0];
            }
            else
                $f=$k;
            $newDirty[$f]=$object->{"*".$f};
        }
        $dirtyFields=$newDirty;

        if($isNew)
            $tFields=$object->__getFields();
        else
            $tFields=$dirtyFields;
        $fieldList=$object->__getFields();

        // En mysql, nos quedamos con el primer nivel de los campos, que es lo que va a determinar la columna.

        $setOnSaveFields=[];
        $nSetOnSave=0;
        foreach ($tFields as $key => $value)
        {
            if(!$value->is_set())
            {
                if(!isset($dirtyFields[$key]))
                {
                    continue;
                }
                if($isNew && $value->__hasDefaultValue())
                    $value->setValue($value->getType()->getValue());
            }
            if($value->__isAlias())
                continue;
            if(($isNew && ($value->getFlags() & \lib\model\types\BaseType::TYPE_SET_ON_SAVE))) {
                $setOnSaveFields[$key]=$value;
                $nSetOnSave++;
                continue;
            }


           // $subVals = $value->serialize($this);
            try
            {
                $subVals=$this->serializeType($value->__getFieldName(),$value);
            }catch(\lib\model\types\BaseTypeException $e)
            {
                _d($e);
                throw new BaseModelException(BaseModelException::ERR_INVALID_SERIALIZER,array("fieldName"=>$this->name,"model"=>$this->model->__getObjectName(),"serializer"=>$serializer));
            }

            // Los tipos compuestos pueden devolver un array

            if (is_array($subVals))
            {
                if(count($subVals)==0)
                    $results[$this->getDestinationColumn($key)]=null;
                else
                {
                    foreach($subVals as $resKey=>$resValue)
                        $results[$this->getDestinationColumn($resKey)]=($resValue===null?null:$resValue);
                }
            }
            else
                $results[$this->getDestinationColumn($key)] = ($subVals===null?null:$subVals);
        }

        // Aunque $results sea cero, si es nuevo, se guarda.
        if(count($results)==0 && !$isNew)
            return;

        if ($isNew)
        {
            $this->insertFromAssociative($object->__getTableName(), [$results]);
        }
        else
        {
            $conds = $this->getIndexExpression($object);

            if (!$conds)
                $conds = array();

            $filters = $object->__getFilter($this->getSerializerType());
            if ($filters)
            {
                $conds = array_merge($conds, $filters["DEF"]["CONDITIONS"]);
            }

            if (count($conds) == 0)
            {
                throw new StorageSerializerException(StorageSerializerException::ERR_NO_ID_FOR_OBJECT);
            }
            $builder = $this->getQueryBuilder(array("BASE"=>array("*"),"CONDITIONS" => $conds), null);
            $q=$builder->build(true);
            $this->updateFromAssociative($object->__getTableName(), $results, $q, false);
        }
        $this->updateOnSaveFields($object,$setOnSaveFields,$isNew);

    }

    function serializeToArray($objects,$fields=null)
    {
        if(!is_array($objects))
            $objects=[$objects];

        $nItems=count($objects);
        // En ES no existe campo Autoincrement, pero el objeto puede tener indices. Se busca un campo AutoIncrement, que
        // va a ser sustituido por un UUID.
        if($nItems == 0)
            return;
        $typeSerializers=$this->getSerializersForObject($objects[0]);
        $indexField=null;
        $func=null;
        $ser=$this;
        $targetFields=$fields==null?array_keys($typeSerializers):$fields;
        $func=function($item) use ($indexField,$typeSerializers,$targetFields,$ser){
            $data=[];
            foreach($targetFields as $k=>$v) {
                if($item->{"*".$v}->__hasValue())
                    $data = array_merge($data, $typeSerializers[$v]->serialize($v, $item->{"*" . $v}, $ser));
            }
            return $data;
        };
        return array_map($func,$objects);
    }

    // A sobreescribir por sistemas de almacenamiento que modifiquen los tipos al guardar.
    function updateOnSaveFields($object,$setOnSaveFields,$isNew)
    {

    }

    // modeldef es una instancia de \lib\reflection\classes\ModelDefinition.
    // extradef es una instancia de \lib\reflection\classes\MysqlOptionsDefinition

    abstract function createStorage($modelDef,$extraDef=null);
    abstract function destroyStorage($object);
    abstract function createDataSpace($spaceDef);
    abstract function destroyDataSpace($spaceDef);
    abstract function deleteByQuery($q,$params=null);
    abstract function unserialize($object,$queryDef=null,$filterValues=null);
    abstract function subLoad($definition,& $relationColumn);
    abstract function count($definition,& $model);
    abstract function useDataSpace($name);
    abstract function existsDataSpace($name);
    abstract function buildQuery($definition,$parameters,$pagingParameters,$getRows=true);
    abstract function fetchAll($queryDef, & $data, & $nRows, & $matchingRows, $params,$pagingParams);
    abstract function fetchCursor($queryDef, & $data, & $nRows, & $matchingRows, $params,$pagingParams);
    abstract function next();
    abstract function getQueryBuilder($definition,$params);
    abstract function insertFromAssociative($target,$data);
    abstract function updateFromAssociative($target,$fields,$query);
}

?>
