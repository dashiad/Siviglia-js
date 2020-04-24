<?php namespace lib\storage\ES\types;

  class File extends BaseType{
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
              return array($name=>$type->getValue());
          return null;
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          // No se hace via setValue, para no provocar el intento de copia del fichero.
          $type->setUnserialized($value[$name]);
      }
     function getSQLDefinition($name,$definition,$serializer)
     {
          return array("NAME"=>$name,"TYPE"=>["type"=>"keyword"]);
     }
  }

?>
