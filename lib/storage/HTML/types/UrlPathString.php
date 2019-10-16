<?php
namespace lib\storage\HTML\types;

// Modela un tipo de dato que es la transformacion de una string, a otra que debe ser unica, y modificada para
// aparecer en links.


class UrlPathString extends _String
{
      function serialize($name,$type,$serializer)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->valueSet)
              return [$name=>htmlentities($type->getValue(),ENT_NOQUOTES,"UTF-8")];
          return "";
      }
      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          // Habria que ver tambien si esta en UTF-8
           if($type->definition["TRIM"])
               $value=trim($value);
          // Escapeado -- Anti-Xss?
           $type->validate($value);
           $type->setValue($value);
      }
   }

