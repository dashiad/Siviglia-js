<?php
/**
 * Class Date
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\Ads\objects\AdManager\serializers\CSV\types;


class Date extends BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $parts=explode("/",$value[$name]);
        $month=$parts[0];
        $day=$parts[1];
        $year=2000+$parts[2];
        $type->setValue($year."-".$month."-".$day);
    }
}
