<?php namespace lib\storage\Mysql\types;


  class Date extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
              return [$name=>"'".$type->getValue()."'"];
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
            $type->setValue($value[$name]);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>"DATE");
      }
  }





?>
