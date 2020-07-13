<?php
namespace lib\storage\Mysql\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.


  class Enum extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->__hasValue())
          {
              $def=$type->getDefinition();
              if(isset($def["MYSQL"]) && isset($def["MYSQL"]["STORE_AS_INTEGER"]))
                  return intval($type->getValue());
              return [$name=>"'".htmlentities($type->getLabel(),ENT_NOQUOTES,"UTF-8")."'"];
          }
          return [$name=>"NULL"];
      }

      function getSQLDefinition($name,$definition,$serializer)
      {
          $default=$definition["DEFAULT"];
          if(!$definition["VALUES"])
              var_dump($definition);
          return array("NAME"=>$name,"TYPE"=>"ENUM('".implode("','",$definition["VALUES"])."') ".(isset($default)?"DEFAULT '".$default."'":""));
      }
  }
