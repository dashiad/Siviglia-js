<?php
namespace lib\storage;
use lib\model\types\TypeFactory;
use lib\storage\ES\ESSerializerException;
use lib\storage\ES\QueryBuilder;

class StorageSerializerException extends \lib\model\BaseException
{
    const ERR_TYPE_SERIALIZER_NOT_FOUND=1;
    const TXT_TYPE_SERIALIZER_NOT_FOUND="No encotrado serializador de [%serializer%] para el tipo [%name%]";
    const ERR_NO_ID_FOR_OBJECT = 2;
    const TXT_NO_ID_FOR_OBJECT="No se ha recibido un identificador para el objeto";
    const ERR_NO_SUCH_OBJECT = 3;
    const TXT_NO_SUCH_OBJECT = "No se encuentra el objeto con identificador [%id%]";
    const ERR_NO_CONNECTION_DETAILS=4;
    const TXT_NO_CONNECTION_DETAILS="No se han proporcionado detalles de conexion";
}
abstract class StorageSerializer
{
    protected $storageManager;
    protected $serializerType;
    protected $name;
    protected $typeSerializers=[];
    protected $definition;
    protected $innerDefinition;
    function __construct($definition,$serType)
    {
        $this->serializerType=$serType;
        $this->definition=$definition;
        $this->name=$definition["NAME"];
        if(isset($definition[$serType]))
            $this->innerDefinition=$definition[$serType];
    }
    function getStorageManager()
    {
        return $this->storageManager;
    }

    function getSerializerType()
	{
		return $this->serializerType;
	}

	function serialize($object)
	{
		$object->setSerializer($this);
		$object->save();
	}
	function setName($name)
    {
        $this->name=$name;
    }
    function getName()
    {
        return $this->name;
    }
    function getConfig()
    {
        return $this->definition;
    }
    function getTypeSerializer($mixedType)
    {
        $className=get_class($mixedType);
        if(isset($this->typeSerializers[$className]))
            return $this->typeSerializers[$className];

        $type=substr($className,strrpos($className,'\\')+1);


        $baseNamespace=$this->getTypeNamespace();
        $typeClass=TypeFactory::includeType(ucfirst(strtolower($type)));
        $name=$type;

        $typeList=array_values(class_parents($typeClass));
        $nEls=array_unshift($typeList,$name);
        for($k=0;$k<$nEls;$k++)
        {
            if($typeList[$k]=='lib\model\types\BaseType')
                break;

            $sName=$baseNamespace.'\\'.$typeList[$k];
            if(@class_exists($sName))
            {
                return new $sName();
            }
        }
            //   clean_debug_backtrace(4);
        throw new StorageSerializerException(StorageSerializerException::ERR_TYPE_SERIALIZER_NOT_FOUND,array("name"=>$type,"serializer"=>$this->getName()));
    }
    function serializeType($name,$mixedType)
    {
        $typeSerializer=$this->getTypeSerializer($mixedType);
        return $typeSerializer->serialize($name,$mixedType,$this);
    }
    function unserializeType($name,$mixedType,$value)
    {
        $typeSerializer=$this->getTypeSerializer($mixedType);
        return $typeSerializer->unserialize($name,$mixedType,$value,$this);
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
    function _store($object, $isNew, $dirtyFields)
    {
        $results = array();
        if($isNew)
            $tFields=$object->__getFields();
        else
            $tFields=$dirtyFields;
        foreach ($tFields as $key => $value)
        {
            if(!$value->is_set())
            {
                if(!isset($dirtyFields[$key]))
                {
                    continue;
                }
                if($isNew && $value->getType()->hasDefaultValue())
                    $value->getType()->setValue($value->getType()->getValue());
            }
            if(($isNew && ($value->getType()->getFlags() & \lib\model\types\BaseType::TYPE_SET_ON_SAVE)) || $value->isAlias())
                continue;

            $subVals = $value->serialize($this->getSerializerType());

            // Los tipos compuestos pueden devolver un array
            if (is_array($subVals))
            {
                if(count($subVals)==0)
                    $results[$key]=null;
                else
                {
                    foreach($subVals as $resKey=>$resValue)
                        $results[$resKey]=($resValue===null?null:$resValue);
                }
            }
            else
                $results[$key] = ($subVals===null?null:$subVals);
        }

        // Aunque $results sea cero, si es nuevo, se guarda.
        if(count($results)==0 && !$isNew)
            return;

        if ($isNew)
        {
            $this->conn->insertFromAssociative($object->__getTableName(), $results);
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

                throw new ESSerializerException(StorageSerializerException::ERR_NO_ID_FOR_OBJECT);
            }

            $builder = $this->getQueryBuilder(array("BASE"=>array("*"),"CONDITIONS" => $conds), null);
            $q=$builder->build(true);

            $this->conn->updateFromAssociative($object->__getTableName(), $results, $q, false);
        }

        foreach ($dirtyFields as $key => $value)
            $value->onModelSaved();
    }
    function getSerializersForObject($obj)
    {
        $fields=$obj->__getFields();
        $result=array();
        foreach($fields as $key=>$value)
        {
            $result[$key]=$this->getTypeSerializer($value->getType());
        }
        return $result;
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
                if($item->{"*".$v}->hasValue())
                    $data = array_merge($data, $typeSerializers[$v]->serialize($v, $item->{"*" . $v}, $ser));
            }
            return $data;
        };
        return array_map($func,$objects);
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
    abstract function getTypeNamespace();
    abstract function getQueryBuilder($definition,$params);




}

?>
