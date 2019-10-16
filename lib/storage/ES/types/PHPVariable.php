<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 16/06/14
 * Time: 23:15
 */
namespace lib\storage\ES\types;

class PHPVariable extends BaseType
{
    function serialize($name,$type,$serializer)
    {
        if($type->hasValue())
            return array($name=>json_encode($type->getValue()));
        else
            return null;
    }
    function unserialize($name,$type,$value,$serializer)
    {
        if($value)
        {
            $type->setValue(json_decode($name[$value],true));
        }
    }
    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>["type"=>"object"]);
    }
}
