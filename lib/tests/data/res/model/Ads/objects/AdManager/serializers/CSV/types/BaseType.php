<?php
/**
 * Class BaseSerializer
 * @package model\ads\AdManager\serializers\types
 *  (c) Smartclip
 */


namespace model\Ads\AdManager\serializers\CSV\types;

class BaseType {
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())return array($name=>$type->getValue());
        return null;
    }
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        if(isset($value[$name]))
            $type->__rawSet($value[$name]);
        else
            $type->__rawSet(null);
    }

}


