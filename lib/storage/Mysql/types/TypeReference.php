<?php
namespace lib\storage\Mysql\types;

class TypeReference extends PHPVariable
{
    var $typeSer;
    var $typeInstance;

    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())
        {
            return [$name=>$type->getRawValue()];
        }
        return [$name=>"NULL"];
    }

    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $type->setRawValue($value[$name]);
    }
}
