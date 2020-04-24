<?php
namespace lib\storage\ES\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.


  class Enum extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
          {
              return array($name=>$type->getLabel());
          }
          return null;
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          $v=$type->getValueFromLabel($name[$value]);
          $type->setValue($v);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>["type"=>"keyword"]);
      }
  }
