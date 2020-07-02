<?php
namespace model\ads\Comscore\serializers\types;


  class BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->__hasValue())
          {
              return $type->value;
          }
          return null;
      }

  }
