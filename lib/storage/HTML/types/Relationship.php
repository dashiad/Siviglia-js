<?php namespace lib\storage\HTML\types;


class Relationship extends BaseType
   {
      function serialize($name,$type,$serializer,$model=null)
      {
          $remoteType=$type->getRelationshipType($name,$type->getParent());
          return \lib\model\types\TypeFactory::serializeType($remoteType,"HTML");
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          $remoteType=$type->getRelationshipType($name,$type->getParent());
          $serializer->unserializeType($name,$remoteType,$value,$model);
          $type->apply($remoteType->getValue(),\lib\model\types\BaseType::VALIDATION_MODE_STRICT);
      }
   }
