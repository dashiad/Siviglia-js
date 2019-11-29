<?php
namespace lib\storage\HTML\types;

  class Enum extends BaseType
   {
      function serialize($name,$type,$serializer,$model=null)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->hasValue())
              return [$name=>htmlentities($type->getLabel(),ENT_NOQUOTES,"UTF-8")];
          return [$name=>""];
      }

      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          if($value===null)
              return;
          $val2=is_numeric($value)?intval($value):$value;
          $type->validate($val2);
          $type->setValue($val2);
          return $value;
      }
   }

