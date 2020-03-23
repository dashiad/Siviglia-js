<?php namespace lib\model\types;
  class AutoIncrement extends Integer
  {
      function __construct($def,$value=null)
      {
          Integer::__construct(array("TYPE"=>"AutoIncrement","MIN"=>0,"MAX"=>9999999999),$value);
          $this->setFlags(BaseType::TYPE_SET_ON_SAVE);
      }
      function _validate($value)
      {
          return true;
      }
      function getRelationshipType()
      {
          return new Integer(array("MIN"=>0,"MAX"=>9999999999));
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/AutoIncrement.php");
          return '\model\reflection\Types\meta\AutoIncrement';
      }
  }
