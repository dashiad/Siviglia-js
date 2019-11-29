<?php
namespace lib\storage\Mysql\types;

// Modela un tipo de dato que es la transformacion de una string, a otra que debe ser unica, y modificada para
// aparecer en links.


  class UrlPathString extends _String
  {
      function serialize($name,$type,$serializer,$model=null)
      {
         $v= $type->hasValue()?"'".mysql_escape_string($type->getValue())."'":"NULL";
         return [$name=>$v];
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $default=$definition["DEFAULT"];
          if(isset($default))
              $defaultExpr=" DEFAULT '".trim($default,"'")."'";
          $charSet=$definition["CHARACTER SET"];
          if(!$charSet)$charSet="utf8";
          $collation=$definition["COLLATE"];
          if(!$collation)$collation="utf8_general_ci";

          $max=$definition["MAXLENGTH"]?$definition["MAXLENGTH"]:45;
          return array("NAME"=>$name,"TYPE"=>"VARCHAR(".$max.") CHARACTER SET ".$charSet." COLLATE ".$collation." ".$defaultExpr);
      }
  }

?>
