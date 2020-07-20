<?php namespace lib\storage\Mysql\types;



  class Relationship extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if(!$type->__hasValue())
              return 'NULL';
          $remoteType=$type->__getRelationshipType($name, null);
          $remoteType->setValue($type->getValue(),\lib\model\types\BaseType::VALIDATION_MODE_NONE);
          $remoteFieldName=$type->getRemoteFields();
          // TODO : Relaciones multiples??
          $serialized= $serializer->serializeType($remoteFieldName[0],$remoteType);
          return [$name=>$serialized[$remoteFieldName[0]]];
      }

      function unserialize($name,$type,$value,$serializer,$model=null)
      {
          $remoteType=$type->__getRelationshipType($name, $type->__getParent());
          $serializer->unserializeType($name,$remoteType,$value,$model);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          $type=\lib\model\types\TypeFactory::getType(["fieldName"=>$name],$definition,null);
          $remoteType=$type->__getRelationshipType($name,null);
          $typeSerializer=$serializer->getTypeSerializer($remoteType);

          return $typeSerializer->getSQLDefinition($name,$remoteType->getDefinition(),$serializer);

      }
  }

