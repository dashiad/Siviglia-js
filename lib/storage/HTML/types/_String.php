<?php namespace lib\storage\HTML\types;

  class _String extends BaseType
   {
      function serialize($name,$type,$serializer,$model=null)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->valueSet)
              return [$name=>htmlentities($type->getValue(),ENT_NOQUOTES,"UTF-8")];
          return "";
      }
      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          if($value!==null && $value!="NULL" && $value!="null")
          {
          // Habria que ver tambien si esta en UTF-8
           if(isset($type->__definition["TRIM"]))
               $value=trim($value);

            // Escapeado -- Anti-Xss?
            $type->validate($value);
            $type->setValue($value);

          }
      }
   }
