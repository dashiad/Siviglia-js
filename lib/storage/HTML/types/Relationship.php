<?php namespace lib\storage\HTML\types;


class Relationship extends BaseType
   {
      function serialize($name,$type,$serializer,$model=null)
      {
          $remoteType=$type->getRelationshipType();
          return \lib\model\types\TypeFactory::serializeType($remoteType,"HTML");
      }
      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          $remoteType=$type->getRelationshipType();
          $serializer->unserializeType($name,$remoteType,$value,$model);
          $type->setValue($remoteType->getValue());
      }
   }
