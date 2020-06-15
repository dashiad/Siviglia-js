<?php namespace lib\storage\ES\types;



  class Relationship extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if(!$type->hasValue())
              return 'NULL';
          $remoteType=$type->getRelationshipType($name,$type->getParent());
          $remoteType->apply($type->getValue(),\lib\model\types\BaseType::VALIDATION_MODE_NONE);
          $serialized= $serializer->serializeType($remoteType);
          return [$name=>$serialized];
      }

      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          $remoteType=$type->getRelationshipType($name,$type->getParent());
          $serializer->unserializeType($name,$remoteType,$value,$model);
          $type->setValue($remoteType->getValue(),\lib\model\types\BaseType::VALIDATION_MODE_NONE);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $type=\lib\model\types\TypeFactory::getType(["fieldName"=>$name,"path"=>"/"],$definition,null);
          $remoteType=$type->getRelationshipType($name,null);
          $serializer=$serializer->getTypeSerializer($remoteType,$serializer);
          return $serializer->getSQLDefinition($name,$remoteType->getDefinition());

      }
  }

