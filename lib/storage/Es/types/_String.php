<?php namespace lib\storage\ES\types;

  class _String extends BaseType
  {
      function getSQLDefinition($name,$definition,$serializer)
      {
          return array("NAME"=>$name,"TYPE"=>["type"=>"keyword"],"index"=>true);
      }
  }
