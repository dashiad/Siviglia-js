<?php
namespace model\ads\SmartConfig\types;

abstract class BaseType extends \lib\model\types\BaseType {

    public function __construct()
    {
    	//
    }

    public function serialize($name, $type, $serializer, $model=null)
    {
        if($type->__hasValue()) {
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
