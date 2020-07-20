<?php
  namespace lib\model\types;
  class ArrayException extends BaseTypeException
  {
      const ERR_INVALID_ARRAY_TYPE=101;
      const ERR_INVALID_ARRAY_VALUE=102;
      const TXT_INVALID_ARRAY_TYPE="Type [%type%] invalid for array";
      const TXT_INVALID_ARRAY_VALUE="Value [%value%] invalid for array";
  }

  class _Array extends BaseType implements \ArrayAccess,\Countable
  {
      var $subTypeDef;
      var $subObjects;
      var $remoteTypeName;

      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          $this->subTypeDef=$def["ELEMENTS"];
          $this->subObjects=null;
          $ins=$this->getSubtypeInstance(0);
          $this->remoteTypeName=get_class($ins);
          parent::__construct($name,$def,$parentType, $value,$validationMode);
      }
      function getSubTypeDef()
      {
          return $this->subTypeDef;
      }
      function __setValidationMode($mode)
      {
          $this->validationMode=$mode;
          if($this->subObjects!==null)
          {
              for($k=0;$k<count($this->subObjects);$k++)
                  $this->subObjects[$k]->__setValidationMode($mode);
          }
      }
      function getSubtypeInstance($fieldName)
      {
         return TypeFactory::getType(["fieldName"=>$fieldName,"path"=>$this->fieldNamePath],$this->subTypeDef,$this,null,$this->validationMode);
      }

      function _setValue($val,$validationMode=null)
      {
          if($validationMode===null)
              $validationMode=$this->validationMode;
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
                    $v->__setParent($this,$k);
                    $v->__setValidationMode($this->validationMode);
                    $this->subObjects[]=$v;
                }
                else
                    throw new ArrayException(ArrayException::ERR_INVALID_ARRAY_TYPE,["type"=>get_class($v)],$this);
              }
              else
              {
                  $ninst=$this->getSubtypeInstance($k);
                  // Tenemos que asignarlo primero al array, y luego darle valor.
                  // Esto es necesario porque en apply, se va a preguntar si el campo es requerido,
                  // usando el path del valor. El path va a hacer referencia al array
                  // (va a ser del tipo /arrayType/0) , y si el array aun no lo tiene asignado, va a dar error.
                  $this->subObjects[]=$ninst;
                  $ninst->apply($v,$validationMode);

              }
          }
          if(count($this->subObjects)>0)
            $this->valueSet=true;
      }

      function _validate($value)
      {
        if(!is_array($value))
                $value=array($value);
          if($this->__onlyValidating) {
              for ($k = 0; $k < count($value); $k++) {
                  $remoteType = $this->getSubtypeInstance($k);
                  $remoteClass = get_class($remoteType);
                  if (is_a($value[$k], $remoteClass))
                      continue;
                  if (!$remoteType->validate($value[$k]))
                      return false;
              }
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
      function __getReference()
      {
          return $this;
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
      function __getFieldDefinition($field)
      {

          return $ins->getDefinition();
      }
      // TODO : Este metodo es muy problematico en arrays.
      // En arrays, los "campos" son los indices.
      // Mientras un container tiene paths como /container/campo1, los arrays tienen paths del
      // tipo /array/0 . Mientras el campo del container siempre existe, tenga valor o no,
      // los indices del array sólo existen si se ha asignado un valor. Esto es importante, ya
      // que si se quiere validar si un valor es correcto, sin asignarlo previamente, al container
      // se le puede preguntar por la definicion de /container/campo1, pero a un array, no se
      // le puede preguntar por /array/0, ya que no existe (ya que sólo se está validando, no asignando).
      // Es por eso que aqui, si un campo no existe, el array "se lo inventa", creando una instancia.

      function __getField($field)
      {
          if(isset($this->subObjects[$field]) || $field=="[[KEYS]]")
              return $this->__get($field);
          $ins=$this->getSubtypeInstance(0);
          return $ins;
      }
      function __get($index)
      {
          if($index=="[[KEYS]]")
              return array_keys($this->subObjects);
          if($index=="[[SOURCE]]")
          {
              $result=[];
              for($k=0;$k<count($this->subObjects);$k++)
              {
                  $result[]=["LABEL"=>$this->subObjects[$k]->getValue(),"VALUE"=>$index];
              }
              return $result;
          }
          return $this->subObjects[$index];
      }
      function __set($index,$value)
      {
          if(!isset($this->subObjects[$index]))
              $this->subObjects[$index]=$this->getSubtypeInstance($index);
          $this->subObjects[$index]->apply($value);
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
            $this->subObjects[$index]->destruct();
            unset($this->subObjects[$index]);
      }
      function getApplicableErrors()
      {
          $errors=parent::getApplicableErrors();
          $errors[get_class($this)."Exception"][ArrayTypeException::ERR_ERROR_AT]=ArrayTypeException::TXT_ERROR_AT;
          $subType=TypeFactory::getType(null,$this->subTypeDef,null);
          $subType->__setParent($this);
          $errorsSubType=$subType->getApplicableErrors();
          return array_merge($errors,$errorsSubType);
      }
      function __clear()
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

      function __getEmptyValue()
      {
          return [];
      }
      function __getTypeFromPath($path)
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
          return $type->__getTypeFromPath($path);
      }

  }
