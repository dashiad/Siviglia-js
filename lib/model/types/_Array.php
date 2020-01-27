<?php
  namespace lib\model\types;
  class ArrayException extends BaseTypeException
  {
      const ERR_INVALID_ARRAY_TYPE=101;
      const ERR_INVALID_ARRAY_VALUE=102;
      const TXT_INVALID_ARRAY_TYPE="Type [%type%] invalid for array";
      const TXT_INVALID_ARRAY_VALUE="Value [%value%] invalid for array";
  }

  class _Array extends BaseContainer implements \ArrayAccess
  {
      var $subTypeDef;
      var $subObjects;
      var $remoteTypeName;

      function __construct($def)
      {
          $this->subTypeDef=$def["ELEMENTS"];
          $this->subObjects=null;
          $ins=$this->getSubtypeInstance(0);
          $this->remoteTypeName=get_class($ins);
          parent::__construct($def);
      }
      function getSubTypeDef()
      {
          return $this->subTypeDef;
      }
      function getSubtypeInstance($fieldName)
      {
         $instance= TypeFactory::getType(null,$this->subTypeDef,null);
         $instance->setParent($this,$fieldName);
         return $instance;
      }
      function _setValue($val)
      {
          $this->valueSet=false;
          $className=$this->remoteTypeName;
          $this->subObjects=[];
          for($k=0;$k<count($val);$k++)
          {
              $v=$val[$k];
              if(is_object($v))
              {
                if(is_a($v,$className))
                {
                    $this->subObjects[]=$v;
                }
                else
                    throw new ArrayException(ArrayException::ERR_INVALID_ARRAY_TYPE,["type"=>get_class($v)],$this);
              }
              else
              {
                  $ninst=$this->getSubtypeInstance($k);
                  $ninst->__rawSet($v);
                  $this->subObjects[]=$ninst;
              }
          }
          if(count($this->subObjects)>0)
            $this->valueSet=true;
      }

      function _validate($value)
      {
        if(!is_array($value))
                $value=array($value);

         for($k=0;$k<count($value);$k++)
         {
             $remoteType=$this->getSubtypeInstance($k);
             $remoteClass=get_class($remoteType);
             if(is_a($value[$k],$remoteClass))
                 continue;
             if(!$remoteType->validate($value[$k]))
                 return false;
         }
         return true;
      }
      function _getValue()
      {
          $v=[];
          for($k=0;$k<count($this->subObjects);$k++)
          {
              $v[]=$this->subObjects[$k]->getValue();
          }
          return $v;
      }

      function count()
      {
          if($this->valueSet)
              return count($this->subObjects);
          return false;
      }

      function _equals($value)
      {
          if(($this->subObjects===null && $value!==null) ||
              ($this->subObjects!==null && $value===null))
              return false;
          if(count($value)!=count($this->subObjects))
          {
              return false;
          }
          for($k=0;$k<count($this->subObjects);$k++)
          {
              if(is_object($value))
              {
                  if(!$this->subObjects[$k]->equals($value[$k]->getValue()))
                      return false;
              }
              else {
                  if (!$this->subObjects[$k]->equals($value[$k]))
                      return false;
              }
          }
          return true;
      }

      function __toString()
      {
          if($this->subObjects==null)
              return "[NULL]";
          $parts=[];
          for($k=0;$k<count($this->subObjects);$k++)
          {
              $parts[]=$this->subObjects[$k]->__toString();
          }
         return implode(",",$parts);
      }

      function offsetExists($index)
      {
          if(!$this->valueSet)
              return false;
          return isset($this->subObjects[$index]);
      }
      function __get($index)
      {
          return $this->subObjects[$index];
      }
      function __set($index,$value)
      {
          $this->subObjects[$index]->setValue($value);
      }
      function offsetGet($index)
      {
          return $this->subObjects[$index];
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
          $subType->setParent($this);
          $errorsSubType=$subType->getApplicableErrors();
          return array_merge($errors,$errorsSubType);
      }
      function _clear()
      {
          $this->subObjects=null;
      }
      function _copy($ins)
      {
          $n=$ins->count();
          $this->subObjects=[];
          for($k=0;$k<$n;$k++)
          {
            $subins=$this->getSubtypeInstance($k);
            $subins->copy($ins[$n]);
            $this->subObjects[]=$subins;
          }
          if($n>0)
              $this->valueSet=true;
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/meta/_Array");
          return '\model\reflection\Types\meta\_Array';
      }

      function __getPathProperty($pathProperty,$mode)
      {

          if(is_numeric($pathProperty))
          {
              return $this->subObjects[intval($pathProperty)];
          }
          if($pathProperty[0]=="{")
          {
              $pathProperty=substr($pathProperty,1,-1);
              $results=[];
              for($k=0;$k<count($this->subObjects);$k++)
                  $results[] = $this->subObjects[$k]->getPath($pathProperty);
              return $results;
          }
          if($pathProperty=="length")
              return count($this->subObjects);

      }
      function getEmptyValue()
      {
          return [];
      }
      function getTypeFromPath($path)
      {
          if(!is_array($path))
          {
              $path=explode("/",$path);
              if($path[0]=="")
                  array_shift($path);
          }
          if(count($path)==0)
              return $this;
          $type=$this->getSubtypeInstance($path[0]);
          return $type->getTypeFromPath($path);
      }
  }
