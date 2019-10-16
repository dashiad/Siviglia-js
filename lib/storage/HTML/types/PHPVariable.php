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
    function serialize($name,$type,$serializer)
    {
        if($type->hasValue())
            return [$name=>json_encode($type->value)];
        return '';
    }
}
