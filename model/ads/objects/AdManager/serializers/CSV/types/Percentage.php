<?php
/**
 * Class Percentage
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\ads\AdManager\serializers\types;


class Percentage extends BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $value=floatval(str_replace("%","",$value));
        $type->setValue($value);
    }
}
