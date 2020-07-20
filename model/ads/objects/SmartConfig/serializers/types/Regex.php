<?php
namespace model\ads\SmartConfig\serializers\types;

class Regex extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->__hasValue())
        {
            return [$name=>$type->value];
        }
        return null;
    }
}
