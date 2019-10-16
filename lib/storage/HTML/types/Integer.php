<?php namespace lib\storage\HTML\types;

   class Integer extends BaseType
   {
      function serialize($name,$type,$serializer)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->hasValue())
              return [$name=>htmlentities($type->getValue(),ENT_NOQUOTES,"UTF-8")];
          return "";
      }
      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          if($value!==null && is_numeric($value))
          {
            $inted=intval($value);
            $type->setValue($inted);
          }
      }
   }

