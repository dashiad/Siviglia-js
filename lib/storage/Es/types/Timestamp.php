<?php
namespace lib\storage\ES\types;

class Timestamp extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->__hasValue()) {
            $curVal=$type->getValue();
            $formatted=date("c",$curVal);
            return array($name => $formatted);
        }
        // TODO si no?
        return null;
    }
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        if(intval($value[$name])==$value)
            $type->setValue(intval($value[$name]/1000));
        else
        {
            // TODO: Esto deberia ser mas robusto.. Estoy suponiendo que entonces es una fecha ISO.
            $type->setValue(date("U",strtotime($value[$name])));
        }
    }
    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>["type"=>"Date","format"=>"epoch_millis"]);
    }
}
