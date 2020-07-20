<?php 
namespace model\ads\Comscore\serializers\types;


  class _Array extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          $values = [];
          foreach ($type->subObjects as $value) {
              $values[] = $value->value;
          }
	      return json_encode($values, JSON_UNESCAPED_SLASHES);
      }
  }
