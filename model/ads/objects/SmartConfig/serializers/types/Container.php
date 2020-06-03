<?php
namespace model\ads\SmartConfig\serializers\types;

class Container extends BaseType
{
    function serialize($name,$type,$serializer,$model=null)
    {
        if($type->hasValue())
        {
            return [$name=>$type->value];
        }
        return null;
    }
}
