<?php namespace lib\storage\ES\types;

  class Text extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          $v= $type->hasValue()?$type->getValue():null;
          return array($name=>$v);
      }

      function getSQLDefinition($name,$definition,$serializer)
      {

          return array("NAME"=>$name,["type"=>"text"]);
      }
  }

