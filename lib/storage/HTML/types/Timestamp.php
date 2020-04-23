<?php


namespace lib\storage\HTML\types;


class Timestamp
{
    function serialize($name,$type,$serializer,$model=null)
    {
        return [$name=>$type->getValue()];
    }
    function unserialize($name,$type,$val,$serializer)
    {

        if($val==null)
            return;
        $value=$val;
        if($value[$name]=='')
        {
            return $type->clear();
        }
        $parsed=intval($val[$name]/1000);
        $type->validate($parsed);
        $type->setValue($parsed);
    }
}