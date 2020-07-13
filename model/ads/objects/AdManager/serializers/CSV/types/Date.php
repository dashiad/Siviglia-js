<?php
/**
 * Class Date
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\ads\AdManager\serializers\types;


class Date extends \model\ads\AdManager\serializers\CSV\types\BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $parts=explode("/",$value);
        $month=$parts[0];
        $day=$parts[1];
        $year=2000+$parts[2];
        $type->setValue($year."-".$month."-".$day);
    }
}
