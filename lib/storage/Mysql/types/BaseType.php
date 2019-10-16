<?php namespace lib\storage\Mysql\types;

  abstract class BaseType {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())return [$name=>$type->getValue()];
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer)
      {
          $type->__rawSet($value[$name]);
      }
      abstract function getSQLDefinition($name,$definition,$serializer);
  }

