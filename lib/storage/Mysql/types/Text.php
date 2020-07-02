<?php namespace lib\storage\Mysql\types;

  class Text extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          $v= $type->__hasValue()?"'".preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $type->getValue())."'":"NULL";
          return [$name=>$v];
      }

      function getSQLDefinition($name,$definition,$serializer)
      {

          $charSet=$definition["CHARACTER SET"];
          if(!$charSet)$charSet="utf8";
          $collation=$definition["COLLATE"];
          if(!$collation)$collation="utf8_general_ci";

          return array("NAME"=>$name,"TYPE"=>"TEXT CHARACTER SET ".$charSet." COLLATE ".$collation." ".$defaultExpr);
      }
  }

