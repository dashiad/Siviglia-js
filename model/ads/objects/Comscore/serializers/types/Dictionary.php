<?php
namespace lib\storage\Comscore\types;

class Dictionary extends BaseType
{
    function serialize($name, $type, $serializer, $model=null)
    {
        return ($type->hasValue()) ? [$name => $type->getValue()] : [$name => null];
    }
}
