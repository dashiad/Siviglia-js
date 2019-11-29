<?php namespace lib\storage\ES\types;

  class Boolean extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
          {
              $val=$type->getValue();
              if($val===true || $val==="true" || $val==1)
                  return array($name=>true);
              return array($name=>false);
          }
          return NULL;
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
			if($value[$name])
                $type->setValue(true);
            else
                $type->setValue(false);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>["type"=>"boolean"]);
      }
  }




?>
