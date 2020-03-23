<?php namespace lib\model\types;
  class Boolean extends BaseType
  {
      function __construct($def,$val=null)
      {
          BaseType::__construct($def,$val);
      }
      function _setValue($val)
      {
          $this->valueSet=true;
          $this->value=($val!=0)?true:false;
      }
      function _getValue()
      {
          return $this->value;
      }
      function _equals($val)
      {
          return $this->value===$val;
      }
      function _copy($val){
          $this->value=$val->getValue();
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Boolean");
          return '\model\reflection\Types\meta\Boolean';
      }
      function _validate($val)
      {
          return true;
      }

  }

