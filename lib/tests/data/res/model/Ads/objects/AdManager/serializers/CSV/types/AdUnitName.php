<?php
/**
 * Class AdUnitName
 * @package model\Ads\objects\AdManager\serializers\CSV\types
 *  (c) Smartclip
 */


namespace model\Ads\objects\AdManager\serializers\CSV\types;


class AdUnitName extends BaseType
{
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $parts=explode("#",$value[$name]);
        $model->IO=$parts[1];
        $type->setValue($parts[0]);
    }
}
