<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 16/06/14
 * Time: 23:15
 */
namespace lib\storage\Mysql\types;

class PHPVariable extends BaseType
{
    function serialize($name,$type,$serializer)
    {
        if($type->hasValue())
            return [$name=>"'".mysql_escape_string(serialize($type->getValue()))."'"];
        else
            return [$name=>"NULL"];
    }
    function unserialize($name,$type,$value,$serializer)
    {
        if(isset($value[$name]))
        {
            $type->setValue(unserialize($value[$name]));
        }

    }
    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>"BLOB");
    }
}
