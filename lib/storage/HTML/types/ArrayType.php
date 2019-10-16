<?php
  namespace lib\storage\HTML\types;


  class ArrayType  extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          if($type->hasValue())return [$name=>$type->getValue()];
		  return "";
      }
      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          $type->validate($value);
          $type->setValue($value);
      }
  }
