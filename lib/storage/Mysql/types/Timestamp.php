<?php
namespace lib\storage\Mysql\types;

class Timestamp extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->__hasValue())
            return [$name=>"'".$type->getValue()."'"];
        else
            return [$name=>"'".$type->getValueFromTimestamp()."'"];
    }
    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>"DATETIME");
    }
}
