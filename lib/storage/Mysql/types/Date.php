<?php namespace lib\storage\Mysql\types;


  class Date extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())
              return [$name=>"'".$type->getValue()."'"];
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer)
      {
            $type->setValue($value[$name]);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>["type"=>"Date","format"=>"yyyy-MM-dd"]);
      }
  }





?>
