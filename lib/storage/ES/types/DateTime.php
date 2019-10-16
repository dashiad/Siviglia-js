<?php namespace lib\storage\ES\types;


  class DateTime extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())
              return [$name=>"'".$type->getValue()."'"];
          return null;
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>["type"=>"date","format"=>"yyyy-MM-dd HH:mm:ss"]);
      }
  }
