<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 16/06/14
 * Time: 23:15
 */
namespace lib\storage\HTML\types;

class PHPVariable extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())
            return [$name=>json_encode($type->value)];
        return '';
    }
    function unserialize($name,$type,$val,$serializer)
    {
        $s=json_decode($val[$name],true);
        $type->setValue($s);

    }
}
