<?php namespace lib\model\types;
  class AutoIncrement extends Integer
  {
      function __construct($def,$value=null)
      {
          Integer::__construct(array("TYPE"=>"AutoIncrement","MIN"=>0,"MAX"=>9999999999),$value);
          $this->setFlags(BaseType::TYPE_SET_ON_SAVE);
      }

      function validate($value)
      {
          return true;
      }
      function setValue($val)
      {
          Integer::setValue($val);
      }
      function getRelationshipType()
      {
          return new Integer(array("MIN"=>0,"MAX"=>9999999999));
      }
  }
