<?php
/**
 * Class TypeSerializer
 * @package platform\storage
 *  (c) Smartclip
 */


namespace lib\storage;

use lib\model\types\TypeFactory;

class TypeSerializerException extends \lib\Model\BaseException
{
    const ERR_TYPE_SERIALIZER_NOT_FOUND=1;
    const TXT_TYPE_SERIALIZER_NOT_FOUND="No encotrado serializador de [%serializer%] para el tipo [%name%]";
}

abstract class TypeSerializer
{
    protected $typeSerializers=[];
    protected $serializerType;
    protected $name;
    protected $columnMap;
    protected $flippedColumnMap;
    protected $cache=[];
    function __construct($definition,$serType)
    {
        $this->serializerType=$serType;
        $this->definition=$definition;
        if(isset($this->definition["columnMap"]))
            $this->columnMap=$this->definition["columnMap"];
        $this->name=$definition["NAME"];
        if(isset($definition[$serType]))
            $this->innerDefinition=$definition[$serType];
    }
    function setName($name)
    {
        $this->name=$name;
    }
    function getSerializerType()
    {
        return $this->serializerType;
    }
    function getTypeSerializer($mixedType)
    {

        $className=get_class($mixedType);
        if(isset($this->cache[$className]))
        {
            $name=$this->cache[$className];
            return new $name();
        }
        if(isset($this->typeSerializers[$className]))
            return $this->typeSerializers[$className];

        $exploded=explode('\\',$className);
        $unNamespaced=$exploded[count($exploded)-1];

        if($exploded[0]=="lib") {
            $type = $unNamespaced;
        }
        else
            $type=$className;

        $baseNamespace=$this->getTypeNamespace();

        $typeClass=TypeFactory::includeType($type);
        $name=$type;

        $typeList=array_values(class_parents($typeClass));
        $nEls=array_unshift($typeList,$name);
        for($k=0;$k<$nEls;$k++)
        {
            $exploded=explode('\\',$typeList[$k]);
            $unNamespaced=$exploded[count($exploded)-1];
            $sName = $baseNamespace.'\\'.$unNamespaced;



            if(@class_exists($sName))
            {
                $this->cache[$className]=$sName;
                return new $sName();
            }
        }
        //   clean_debug_backtrace(4);
        throw new TypeSerializerException(TypeSerializerException::ERR_TYPE_SERIALIZER_NOT_FOUND,array("name"=>$type,"typeserializer"=>get_class($this)));
    }
    function serializeType($name,$mixedType)
    {
        $typeSerializer=$this->getTypeSerializer($mixedType);
        return $typeSerializer->serialize($name,$mixedType,$this);
    }
    function unserializeType($name,$mixedType,$value,$model)
    {
        $typeSerializer=$this->getTypeSerializer($mixedType);
        return $typeSerializer->unserialize($name,$mixedType,$value,$this,$model);
    }
    function unserializeObjectFromData($object,$data)
    {
        $data=$this->mapIncomingColumns($data);
        $serializers=$this->getSerializersForObject($object);
        foreach ($data as $key => $value)
            $serializers[$key]->unserialize($key,$object->{"*".$key},$data,$this,$object);
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
    function getMappedColumn($sourceColumn)
    {
        if(!isset($this->columnMap))
            return $sourceColumn;
        return $this->columnMap[$sourceColumn];
    }
    function getDestinationColumn($localColumn)
    {
        if(!$this->isset($this->columnMap))
            return $localColumn;
        if(!$this->isset($this->flippedColumnMap))
            $this->flippedColumnMap=array_flip($this->columnMap);
        return $this->flippedColumnMap[$localColumn];
    }
    function mapIncomingColumns($data)
    {
        if(!isset($this->columnMap))
            return $data;
        $newD=[];
        foreach($data as $k=>$v)
        {
            $newD[$this->getMappedColumn($k)]=$v;
        }
        return $newD;
    }
    abstract function getTypeNamespace();
}
