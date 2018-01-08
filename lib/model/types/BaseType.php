<?php namespace lib\model\types;

  class BaseTypeException extends \lib\model\BaseException{
      
      const ERR_UNSET=1;
      const ERR_INVALID=2;
      const ERR_TYPE_NOT_FOUND=3;
      const ERR_INCOMPLETE_TYPE=4;
      const ERR_SERIALIZER_NOT_FOUND=7;
      const ERR_TYPE_NOT_EDITABLE=8;

      var $params;
      public function __construct($code,$params=null)
      {           
          $this->params=$params;       
          parent::__construct($code,$params);
      }      
  }


  abstract class BaseType
  {      
      var $valueSet=false;
      var $value;
      var $definition;
      var $flags=0;

      const TYPE_SET_ON_SAVE=0x1;
      const TYPE_SET_ON_ACCESS=0x2;
      const TYPE_IS_FILE=0x4;
      const TYPE_REQUIRES_SAVE=0x8;
      const TYPE_NOT_EDITABLE=0x10;
      const TYPE_NOT_MODIFIED_ON_NULL=0x20;
      const TYPE_REQUIRES_UPDATE_ON_NEW=0x40;
          
      function __construct($def,$neutralValue=null)
      {
          $this->definition=$def;
          if($neutralValue!==null)          
            $this->setValue($neutralValue);                    
      }      

      function setFlags($flags)
      {
          $this->flags|=$flags;
      }
      function getFlags()
      {
          return $this->flags;
      }

      function setValue($val)
      {
          //if($this->validate($val))
          //{
           /* if($val===null || !isset($val))
            {
              $this->valueSet=false;
              $this->value=null;
            }
            else
            {
              $this->valueSet=true;
              $this->value=$val;      
            }*/
          //}
          $this->valueSet=true;
          $this->value=$val;
      }
      
      function validate($value)
      {                       
            return true;                                            
      }

      function postValidate($value)
      {
          return true;
      }
      function hasValue()
      {
          return $this->valueSet || $this->hasDefaultValue() || ($this->flags & BaseType::TYPE_SET_ON_SAVE) || ($this->flags & BaseType::TYPE_SET_ON_ACCESS);
      }
      function hasOwnValue()
      {
          return $this->valueSet;
      }
      function copy($type)
      {
          
          if($type->hasValue())
          {

              $this->valueSet=true;
              $this->value=$type->getValue();

          }
          else
          {
              if($this->definition["TYPE"]=="State")
                  echo "COPIANDO SIN VALUE\n";
              $this->valueSet=false;
              $this->value=null;
          }
      }
      function equals($value)
      {          
          if($this->value===null)
              return false;
          return $this->value==$value;
      }
      // Warning : sin validacion.Acepta cualquier cosa.Usado por los formularios cuando un campo es erroneo.
      function __rawSet($value)
      {
          $this->value=$value;
          $this->valueSet=true;
      }
      function set($value)
      {
          if(is_object($value) && get_class($value)==get_class($this))
              return $this->copy($value);
          return $this->setValue($value);
      }
      function is_set()
      {
          if($this->valueSet)return true;

          if(!($this->flags & BaseType::TYPE_SET_ON_SAVE) &&
             !($this->flags & BaseType::TYPE_SET_ON_ACCESS))
              return false;
          return true;
      }
      function clear()
      {
          $this->valueSet=true;
          $this->value=null;
      }
      function isEditable()
      {
          return !($this->flags & BaseType::TYPE_NOT_EDITABLE);
      }
      function get()
      {
          return $this->getValue();
      }
      function getValue()
      {
          if($this->valueSet)
            return $this->value; 
          if($this->hasDefaultValue())
            return $this->getDefaultValue();
          return null;          
      }
      function __toString()
      {
          
          if(!$this->valueSet)
          {
              return "";
          }
          return (string)$this->value;
      }
      function hasDefaultValue()
      {

          return isset($this->definition["DEFAULT"]) && $this->definition["DEFAULT"]!="NULL" && $this->definition["DEFAULT"]!==null && $this->definition["DEFAULT"]!=="";
      }
      function getDefaultValue()
      {
          if(!isset($this->definition["DEFAULT"]))
              return false;
          $def=strtolower($this->definition["DEFAULT"]);
          if($def==="null")
              return false;
          return $this->definition["DEFAULT"];
      }
      function setDefaultValue($val)
      {
          $this->definition["DEFAULT"]=$val;
      }
      function getRelationshipType()
      {
          return $this;
      }
      function getDefinition()
      {
          if(!isset($this->definition["TYPE"]))
          {
              $parts=explode("\\",get_class($this));
              $this->definition["TYPE"]=$parts[count($parts)-1];
          }
          return $this->definition;
      }
      function isEmpty()
      {
          return $this->valueSet==false || $this->value==="" || $this->value===null;
      }

  }


  class BaseTypeHTMLSerializer
  {
      function serialize($type)
      {
          if($type->hasValue())return $type->getValue();
		  return "";
      }
      function unserialize($type,$value)
      {
          if($value!==null)
          {
            $type->validate($value);
            $type->setValue($value);
          }
      }
  }

  abstract class BaseTypeMYSQLSerializer {
      function serialize($type)
      {
          if($type->hasValue())return $type->getValue();
          return "NULL";
      }
      function unserialize($type,$value)
      {
          $type->__rawSet($value);
      }
      abstract function getSQLDefinition($name,$definition);
  }

    abstract class BaseTypeCASSSerializer {
      function serialize($type)
      {
          if($type->hasValue())return $type->getValue();
          return "NULL";
      }
      function unserialize($value)
      {
          return $value;
      }
      abstract function getCASSDefinition($name,$definition);
  }

  interface ISaveableType
  {
       function onSave($model);
       function onSaved($model);
  }
?>
