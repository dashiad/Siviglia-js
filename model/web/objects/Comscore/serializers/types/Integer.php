<?php namespace lib\storage\Comscore\types;


  class Integer extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
          {
              return [$name=>$type->getValue()];
          }
          return [$name => "NULL"];
      }

      function getSQLDefinition($name,$definition,$serializer)
      {
          // ...
      }
  }
