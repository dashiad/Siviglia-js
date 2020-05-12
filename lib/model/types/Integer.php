<?php namespace lib\model\types;

  class IntegerException extends BaseTypeException {
      const ERR_TOO_SMALL=100;
      const ERR_TOO_BIG=101;
      const ERR_NOT_A_NUMBER=102;

      const TXT_TOO_SMALL='Minimum value is [%min%]';
      const TXT_TOO_BIG='Maximum value is [%max%]';
      const TXT_NOT_A_NUMBER='Invalid number';
  }
  class Integer extends BaseType
  {

      function _setValue($val,$validationMode=null)
      {
          $this->value=intval($val);
          $this->valueSet=true;
      }
      function _getValue()
      {
          return $this->value;
      }
      function _equals($val)
      {
          return $this->value===intval($val);
      }
      function _copy($val){
          $this->value=$val->getValue();
          $this->valueSet=true;
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Integer.php");
          return '\model\reflection\Types\meta\Integer';
      }

      function _validate($value)
      {
          $value=trim($value);
          if(!preg_match("/^(?:[0-9]+)+$/",$value))
              throw new IntegerException(IntegerException::ERR_NOT_A_NUMBER,["val"=>$value],$this);

          if(isset($this->definition["MIN"]))
          {
              if($value < intval($this->definition["MIN"]))
                  throw new IntegerException(IntegerException::ERR_TOO_SMALL,["val"=>$value,"min"=>$this->definition["MIN"]],$this);
          }
          if(isset($this->definition["MAX"]))
          {
              if($value > intval($this->definition["MAX"]))
                throw new IntegerException(IntegerException::ERR_TOO_BIG,["val"=>$value,"max"=>$this->definition["MAX"]],$this);
          }
          return true;
      }
  }
