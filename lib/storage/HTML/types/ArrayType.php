<?php
  namespace lib\storage\HTML\types;


  class _Array  extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
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
