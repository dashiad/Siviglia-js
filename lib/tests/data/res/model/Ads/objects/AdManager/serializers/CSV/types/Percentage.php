<?php
/**
 * Class Percentage
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\Ads\objects\AdManager\serializers\CSV\types;


class Percentage extends BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $value=floatval(str_replace("%","",$value[$name]));
        $type->setValue($value);
    }
}
