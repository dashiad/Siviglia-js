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
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())
            return [$name=>"'".mysqli_escape_string($serializer->getConnection()->getConnection(), serialize($type->getValue()))."'"];
        else
            return [$name=>"NULL"];
    }
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        if(isset($value[$name]))
        {
            $model->{$name}=unserialize($value[$name]);
        }

    }
    function getSQLDefinition($name,$definition,$serializer)
    {
        return array("NAME"=>$name,"TYPE"=>"BLOB");
    }
}
