<?php namespace lib\storage\Mysql\types;

  class File extends BaseType{
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
              return [$name=>"'".mysql_escape_string($type->getValue())."'"];
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          // No se hace via setValue, para no provocar el intento de copia del fichero.
          $type->setUnserialized($value[$name]);
      }
     function getSQLDefinition($name,$definition,$serializer)
     {
          $default=$definition["DEFAULT"];
          return array("NAME"=>$name,"TYPE"=>"CHAR(255)".(isset($default)?" DEFAULT '".$default."'":""));
     }
  }

?>
