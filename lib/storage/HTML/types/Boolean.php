<?php namespace lib\storage\HTML\types;

   class Boolean extends BaseType
   {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->getValue())
              return [$name=>"on"];
          return [$name=>"off"];
      }
       function unserialize($name,$type,$val,$serializer)
       {
           $value=$val[$name];
           if($value===true || $value===false)
               return $type->setValue($value);
           $v=strtolower($value);
           if($v==="true" || $v==="on" || $v==="1")
               return $type->setValue(true);
           $type->setValue(false);
       }
   }
