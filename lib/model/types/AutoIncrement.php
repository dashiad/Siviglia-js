<?php namespace lib\model\types;
  class AutoIncrement extends Integer
  {

      function __construct($name,$def,$parentType=null,$value=null,$validationMode=null)
      {
          Integer::__construct($name,array("TYPE"=>"AutoIncrement","MIN"=>0,"MAX"=>9999999999),$parentType,$value,$validationMode);
          $this->setFlags(BaseType::TYPE_SET_ON_SAVE);
      }
      function _validate($value)
      {
          return true;
      }
      function getRelationshipType($name,$parent)
      {
          return new Integer($name,array("MIN"=>0,"MAX"=>9999999999),$parent,$this->value,$this->validationMode);
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/AutoIncrement.php");
          return '\model\reflection\Types\meta\AutoIncrement';
      }
  }
