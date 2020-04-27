<?php
namespace lib\storage\Comscore\types;

abstract class BaseType {
    public function serialize($name, $type, $serializer, $model=null)
    {
        if($type->hasValue()) {
            return [$name => $type->getValue()];
        } else {
            return [$name => "NULL"];
        }
    }

    public function unserialize($name, $type, $value, $serializer, $model=null)
    {
        if(isset($value[$name])) {
            $model->{$name} = $value[$name];
        } else {
            $model->{$name} = null;
        }
    }
}