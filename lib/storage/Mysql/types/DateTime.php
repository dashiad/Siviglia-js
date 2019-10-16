<?php namespace lib\storage\Mysql\types;


  class DateTime extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())
              return [$name=>"'".$type->getValue()."'"];
          return [$name=>"NULL"];
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>"DATETIME");
      }
  }
