<?php namespace lib\storage\HTML\types;


  class BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())return [$name=>$type->getValue()];
		  return "";
      }
      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          if($value!==null)
          {
              $type->setValue($value);
          }
      }
  }
