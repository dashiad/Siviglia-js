<?php 
namespace model\ads\Comscore\serializers\types;


  class _String extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
         $v= $type->__hasValue()?preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $type->getValue()):"NULL";
         return $v;
      }
  }
