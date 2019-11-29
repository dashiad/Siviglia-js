<?php
namespace lib\storage\ES\types;

class Timestamp extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())
            return array($name=>$type->getValue()*1000);
        else
            return array($name=>$type->getValueFromTimestamp()*1000);
    }
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $type->setValue(intval($value[$name]/1000));
    }
    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>["type"=>"Date","format"=>"epoch_millis"]);
    }
}
