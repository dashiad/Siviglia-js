<?php namespace lib\storage\HTML\types;


class Relationship extends BaseType
   {
      function serialize($name,$type,$serializer,$model=null)
      {
          $remoteType=$type->__getRelationshipType($name,$type->__getParent());
          return \lib\model\types\TypeFactory::serializeType($remoteType,"HTML");
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          $remoteType=$type->__getRelationshipType($name,$type->__getParent());
          $serializer->unserializeType($name,$remoteType,$value,$model);
          $type->apply($remoteType->getValue(),\lib\model\types\BaseType::VALIDATION_MODE_STRICT);
      }
   }
