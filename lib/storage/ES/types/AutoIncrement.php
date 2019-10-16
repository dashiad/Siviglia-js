<?php namespace lib\storage\ES\types;

  class AutoIncrement extends BaseType{
      function serialize($name,$type,$serializer)
      {
          if(!$type->hasValue())
              return NULL;
			return array($name=>$type->value);
      }
      function getSQLDefinition($name,$definition,$serializer)
      {
          throw new \lib\storage\ES\ESSerializerException(\lib\storage\ES\ESSerializerException::ERR_UNSUPPORTED);
      }
  }

