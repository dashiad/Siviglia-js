<?php
/**
 * Class Integer
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\ads\AdManager\serializers\types;


class Integer extends BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $value=intval(str_replace(",","",$value));
        $type->setValue($value);
    }
}
