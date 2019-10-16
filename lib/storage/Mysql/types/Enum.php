<?php
namespace lib\storage\Mysql\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.


  class Enum extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())
          {
              $def=$type->getDefinition();
              if(isset($def["MYSQL_STORE_AS_INTEGER"]))
                  return intval($type->getValue());
              return [$name=>"'".htmlentities($type->getLabel(),ENT_NOQUOTES,"UTF-8")."'"];
          }
          return [$name=>"NULL"];
      }
      function unserialize($name,$type,$value,$serializer)
      {
          $type->setValue($value[$name]);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $default=$definition["DEFAULT"];
          if(!$definition["VALUES"])
              var_dump($definition);
          return array("NAME"=>$name,"TYPE"=>"ENUM('".implode("','",$definition["VALUES"])."') ".(isset($default)?"DEFAULT '".$default."'":""));
      }
  }
