<?php
namespace lib\model\types;

// Modela un tipo de dato que es la transformacion de una string, a otra que debe ser unica, y modificada para 
// aparecer en links.

class UrlPathString extends _String
{    
    function __construct($def,$value=null)
    {
        parent::__construct(
            array("ALLOWHTML"=>false,"TRIM"=>true,"MINLENGTH"=>1,"MAXLENGTH"=>100),
            $value);

    }
}

class UrlPathStringHTMLSerializer extends _StringHTMLSerializer
{
      function serialize($type)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->valueSet)
              return htmlentities($type->getValue(),ENT_NOQUOTES,"UTF-8");
          return "";
      }
      function unserialize($type,$value)
      {          
          // Habria que ver tambien si esta en UTF-8 
           if($type->definition["TRIM"])
               $value=trim($value);
          // Escapeado -- Anti-Xss?
           $type->validate($value);
           $type->setValue($value);
      }
   }

  class UrlPathStringMYSQLSerializer extends _StringMYSQLSerializer
  {
      function serialize($type)
      {
         $v= $type->hasValue()?"'".mysql_escape_string($type->getValue())."'":"NULL";
         return $v;
      }
      function getSQLDefinition($name,$definition)
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
