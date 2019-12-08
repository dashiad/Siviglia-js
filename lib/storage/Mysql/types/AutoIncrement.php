<?php namespace lib\storage\Mysql\types;

  class AutoIncrement extends BaseType{
      function serialize($name,$type,$serializer,$model=null)
      {
          if(!$type->hasValue())
              return NULL;
			return [$name=>$type->value];
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          if(isset($value[$name]))
              $model->{$name}=intval($value[$name]);
          else
              $model->{$name}=null;
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $iSer=new Integer();
          $subDef=$iSer->getSQLDefinition($name,$definition,$serializer);
          return array("NAME"=>$name,"TYPE"=>$subDef["TYPE"]." AUTO_INCREMENT");
      }
  }

