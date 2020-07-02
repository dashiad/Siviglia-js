<?php namespace lib\storage\Mysql\types;

  abstract class BaseType {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->__hasValue())return [$name=>$type->getValue()];
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          if(!isset($value[$name]))
              $value[$name]=null;
            $model->{"*".$name}->apply($value[$name],\lib\model\types\BaseType::VALIDATION_MODE_NONE);
      }
      abstract function getSQLDefinition($name,$definition,$serializer);
  }

