<?php
  namespace lib\model\types;
  class _Array extends BaseType implements \ArrayAccess
  {
      var $subTypeDef;
      const TYPE_NOT_MODIFIED_ON_NULL=0x20;

      function __construct($def,$neutralValue=null)
      {
          $this->subTypeDef=$def["ELEMENTS"];
          parent::__construct($def,$neutralValue);
      }
      function getSubTypeDef()
      {
          return $this->subTypeDef;
      }
      function setValue($val)
      {
          if($val===null || !isset($val))
          {
              $this->valueSet=false;
              $this->value=null;
          }
          $this->valueSet=true;
          $this->value=$val;
      }
      function validate($value)
      {
        if(!is_array($value))
                $value=array($value);
         $remoteType=TypeFactory::getType(null,$this->subTypeDef,null);
         for($k=0;$k<count($value);$k++)
         {
                if(!$remoteType->validate($value[$k]))
                        return false;
         }
         return true;
      }
      function getValue()
      {
          if($this->valueSet)
            return $this->value;
          if(isset($this->definition["DEFAULT"]))
            return explode(",",$this->definition["DEFAULT"]);
          return null;
      }
      function count()
      {
          if($this->valueSet)
              return count($this->value);
          return false;
      }

      function equals($value)
      {
          if(($this->value===null && $value!==null) ||
              ($this->value!==null && $value===null))
              return false;
          if(count($value)!=count($this->value))
          {
              return false;
          }
          for($k=0;$k<count($this->value);$k++)
          {
              if(!$this->value[$k]->equals($value[$k]))
                  return false;
          }
          return true;
      }

      function __toString()
      {
         return implode(",",$this->value);
      }

      function offsetExists($index)
      {
          if(!$this->valueSet)
              return false;
          return isset($this->value[$index]);
      }

      function offsetGet($index)
      {
          return $this->value[$index];
      }
      function offsetSet($index,$newVal)
      {
      }
      function offsetUnset($index)
      {

      }
      function getApplicableErrors()
      {
          $errors=parent::getApplicableErrors();
          $errors[get_class($this)."Exception"][ArrayTypeException::ERR_ERROR_AT]=ArrayTypeException::TXT_ERROR_AT;
          $subType=TypeFactory::getType(null,$this->subTypeDef,null);
          $errorsSubType=$subType->getApplicableErrors();
          return array_merge($errors,$errorsSubType);
      }
  }

  class ArrayTypeMeta
  {
      function getMeta($type)
      {
          $def=$type->getDefinition();
          $subType=$def["ELEMENTS"];
          $def["ELEMENTS"]=\lib\model\types\TypeFactory::getTypeMeta($subType);
          return $def;
      }
  }


