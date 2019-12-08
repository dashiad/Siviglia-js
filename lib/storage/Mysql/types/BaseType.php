<?php namespace lib\storage\Mysql\types;

  abstract class BaseType {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())return [$name=>$type->getValue()];
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          if(isset($value[$name]))
            $model->{$name}=$value[$name];
          else
              $model->{$name}=null;
      }
      abstract function getSQLDefinition($name,$definition,$serializer);
  }

