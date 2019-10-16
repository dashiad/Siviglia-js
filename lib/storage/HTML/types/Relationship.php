<?php namespace lib\storage\HTML\types;


class Relationship extends BaseType
   {
      function serialize($type,$serializer)
      {
          $remoteType=$type->getRelationshipType();
          return \lib\model\types\TypeFactory::serializeType($remoteType,"HTML");
      }
      function unserialize($name,$type,$value,$serializer)
      {
          $remoteType=$type->getRelationshipType();
          $serializer->unserializeType($name,$remoteType,$value);
          $type->setValue($remoteType->getValue());
      }
   }
