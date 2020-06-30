<?php
/**
 * Class Integer
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\Ads\AdManager\serializers\CSV\types;
include_once(__DIR__."/BaseType.php");

class Integer extends BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $value=intval(str_replace(",","",$value[$name]));
        $type->setValue($value);
    }
}
