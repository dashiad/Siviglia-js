<?php
namespace model\ads\SmartConfig\serializers\types;

class Regex extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())
        {
            return $type->value;
        }
        return null;
    }
}
