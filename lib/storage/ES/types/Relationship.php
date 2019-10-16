<?php namespace lib\storage\ES\types;



  class Relationship extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          if(!$type->hasValue())
              return 'NULL';
          $remoteType=$type->getRelationshipType();
          $remoteType->setValue($type->getValue());
          $serialized= $serializer->serializeType($remoteType);
          return [$name=>$serialized];
      }

      function unserialize($name,$type,$value,$serializer)
      {
          $remoteType=$type->getRelationshipType();
          $serializer->unserializeType($name,$remoteType,$value);
          $type->setValue($remoteType->getValue());
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $type=\lib\model\types\TypeFactory::getType(null,$definition);
          $remoteType=$type->getRelationshipType();
          $serializer=$serializer->getTypeSerializer($remoteType,$serializer);
          return $serializer->getSQLDefinition($name,$remoteType->getDefinition());

      }
  }

