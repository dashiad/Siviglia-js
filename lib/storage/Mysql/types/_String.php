<?php namespace lib\storage\Mysql\types;

  class _String extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
         $v= $type->hasValue()?"'".preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $type->getValue())."'":"NULL";
         return [$name=>$v];
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $defaultExpr="";
          if(isset($definition["DEFAULT"])) {
              $default = $definition["DEFAULT"];
              $defaultExpr = " DEFAULT '" . trim($default, "'") . "'";
          }
          $charSet=io($definition,"CHARACTER_SET","utf8");
          $collation=io($definition,"COLLATE","utf8_general_ci");
          $max=io($definition,"MAXLENGTH",45);
          return array("NAME"=>$name,"TYPE"=>"VARCHAR(".$max.") CHARACTER SET ".$charSet." COLLATE ".$collation." ".$defaultExpr);
      }
  }
