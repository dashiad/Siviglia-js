<?php namespace lib\storage\ES\types;

  abstract class BaseType {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())return array($name=>$type->getValue());
          return null;
      }
      function unserialize($name,$type,$value,$serializer)
      {
          $type->__rawSet($value[$name]);
      }
      abstract function getSQLDefinition($name,$definition,$serializer);
  }

