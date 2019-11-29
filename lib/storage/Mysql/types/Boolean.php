<?php namespace lib\storage\Mysql\types;

  class Boolean extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
          {
              $val=$type->getValue();
              if($val===true || $val==="true" || $val==1)
                  return [$name=>1];
              return [$name=>0];
          }
          return [$name=>"NULL"];
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
          $cad="BOOLEAN";
          if(isset($definition["DEFAULT"])) {
              if ($definition["DEFAULT"] == true || $definition["DEFAULT"] == "TRUE")
                  $cad .= " DEFAULT TRUE";
          }
          return array("NAME"=>$name,"TYPE"=>$cad);
      }
  }




?>
