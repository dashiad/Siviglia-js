<?php namespace lib\model\types;
  class Boolean extends BaseType
  {
      function __construct($def,$val=null)
      {
          BaseType::__construct($def,$val);
      }
      function setValue($val)
      {          
          
          BaseType::setValue($val);
      }
  }

  class BooleanTypeMeta extends BaseTypeMeta
      {}

   class BooleanHTMLSerializer extends BaseTypeHTMLSerializer
   {
      function serialize($type)
      {
          if($type->getValue())
              return "on";
          return "off";
      }
       function unserialize($type,$value)
       {
           if($value===true || $value===false)
               return $type->setValue($value);
           $v=strtolower($value);
           if($v==="true" || $v==="on" || $v==="1")
               return $type->setValue(true);
           $type->setValue(false);
       }
   }

  class BooleanMYSQLSerializer extends BaseTypeMYSQLSerializer 
  {
      function serialize($type)
      {
          if($type->hasValue())
          {
              $val=$type->getValue();
              if($val===true || $val==="true" || $val==1)
                  return 1;
              return 0;
          }
          return NULL;
      }
      function unserialize($type,$value)
      {          
			if($value)
                $type->setValue(true);
            else
                $type->setValue(false);
      }
      function getSQLDefinition($name,$definition)
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
