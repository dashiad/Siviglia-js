<?php namespace lib\storage\ES\types;

  abstract class BaseType {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->__hasValue())return array($name=>$type->getValue());
          return null;
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          if(isset($value[$name]))
            $type->__rawSet($value[$name]);
          else
              $type->__rawSet(null);
      }
      abstract function getSQLDefinition($name,$definition,$serializer);
  }

