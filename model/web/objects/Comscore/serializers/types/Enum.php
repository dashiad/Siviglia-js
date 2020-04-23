<?php
namespace lib\storage\Comscore\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.


  class Enum extends BaseType
  {
      function serialize($name, $type, $serializer, $model=null)
      {
          if($type->hasValue())
          {
              //$def = $type->getDefinition();
              //return [$name =>"'".htmlentities($type->getLabel(),ENT_NOQUOTES,"UTF-8")."'"];
              return [$name => $type->getLabel()];
          }
          return [$name=>null];
      }

  }
