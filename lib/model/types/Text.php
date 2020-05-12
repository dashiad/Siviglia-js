<?php namespace lib\model\types;
  class Text extends BaseType
  {
      function _getValue()
      {
          return $this->value;
      }
      function _validate($val)
      {
          return true;
      }
      function _setValue($val,$validationMode=null)
      {
          $this->valueSet=true;
          $this->value=$val;
      }
      function _equals($val)
      {
          return $this->value==$val;
      }
      function _copy($ins)
      {
          $this->value=$ins->value;
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Text.php");
          return '\model\reflection\Types\meta\Text';
      }
  }

