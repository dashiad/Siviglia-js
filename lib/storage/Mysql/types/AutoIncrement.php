<?php namespace lib\storage\Mysql\types;

  class AutoIncrement extends BaseType{
      function serialize($name,$type,$serializer)
      {
          if(!$type->hasValue())
              return NULL;
			return [$name=>$type->value];
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $iSer=new Integer();
          $subDef=$iSer->getSQLDefinition($name,$definition,$serializer);
          return array("NAME"=>$name,"TYPE"=>$subDef["TYPE"]." AUTO_INCREMENT");
      }
  }

