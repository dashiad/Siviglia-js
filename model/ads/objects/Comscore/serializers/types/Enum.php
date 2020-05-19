<?php
namespace model\ads\Comscore\serializers\types;

  class Enum extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
          {
              return $type->value;
          }
          return null;
      }

  }
