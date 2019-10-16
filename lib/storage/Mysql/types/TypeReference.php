<?php
namespace lib\storage\Mysql\types;

class TypeReference extends PHPVariable
{
    var $typeSer;
    var $typeInstance;

    function serialize($name,$type,$serializer)
    {
        if($type->hasValue())
        {
            return [$name=>$type->getRawValue()];
        }
        return [$name=>"NULL"];
    }

    function unserialize($name,$type,$value,$serializer)
    {
        $type->setRawValue($value[$name]);
    }
}
