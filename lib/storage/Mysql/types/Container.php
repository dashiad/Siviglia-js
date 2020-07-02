<?php namespace lib\storage\Mysql\types;


class Container
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->__hasValue())
        {
            $val=$type->getValue();
            return [$name=>"'".json_encode($type->getValue())."'"];
        }
        return [$name=>"NULL"];
    }
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        if($value[$name])
            $type->apply(json_decode($value[$name],true),\lib\model\types\BaseType::VALIDATION_MODE_NONE);
        else
            $type->setValue(null);
    }

    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>"JSON");
    }
}
