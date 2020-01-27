<?php namespace lib\model\types;

  use lib\model\BaseTypedException;

  abstract class BaseType
  {
      var $valueSet=false;
      var $validatingValue=null;
      var $value=null;
      var $definition;
      var $flags=0;
      var $parent;
      var $setOnEmpty;
      const TYPE_SET_ON_SAVE=0x1;
      const TYPE_SET_ON_ACCESS=0x2;
      const TYPE_IS_FILE=0x4;
      const TYPE_REQUIRES_SAVE=0x8;
      const TYPE_NOT_EDITABLE=0x10;
      const TYPE_NOT_MODIFIED_ON_NULL=0x20;
      const TYPE_REQUIRES_UPDATE_ON_NEW=0x40;

      function __construct($def)
      {
          $this->parent=null;
          $this->typeReference=false;
          $this->definition=$def;
          $this->flags=0;
          $this->setOnEmpty=false;
          if(isset($def["SET_ON_EMPTY"]) && $def["SET_ON_EMPTY"]==true)
              $this->setOnEmpty=true;
          if($this->hasDefaultValue() && !isset($definition["DISABLE_DEFAULT"]))
              $this->__rawSet($this->getDefaultValue());
          if(isset($def["FIXED"])) {
              $this->__rawSet($def["FIXED"]);
              $this->flags|=BaseType::TYPE_NOT_EDITABLE;
          }

      }
      function applyDefault()
      {

      }
      function setParent($parent)
      {
          $this->parent=$parent;
      }
      function hasSource()
      {
          return isset($this->definition["SOURCE"]);
      }
      function getSource($validating=false)
      {
          return \lib\model\types\sources\SourceFactory::getSource($this,$this->definition["SOURCE"],$validating);
      }

      function setFlags($flags)
      {
          $this->flags|=$flags;
      }
      function getFlags()
      {
          return $this->flags;
      }

      final function setValue($val)
      {
          if($val===$this->getEmptyValue())
          {
              if($this->setOnEmpty==false)
                    $val=null;
          }

          if($val===null)
          {
              $this->clear();
          }
          else {
              if($this->flags & BaseType::TYPE_NOT_EDITABLE)
                  return;
              if ($this->validate($val))
                  $this->_setValue($val);
          }

      }
      function getEmptyValue()
      {
          return null;
      }
      function isEmptyValue($val)
      {
          return $val===null || $val==="";
      }
      abstract function _setValue($val);

      final function validate($value)
      {

            if($value===null)
                return true;
            $this->validatingValue=$value;
            if(!$this->checkSource($value))
                throw new BaseTypeException(BaseTypeException::ERR_INVALID,["value"=>$value]);
            $res=$this->_validate($value);
            $this->validatingValue=null;
            return $res;
      }
      function getValidatingValue()
      {
          return $this->validatingValue;
      }
      function checkSource($value)
      {
          if(!$this->hasSource())
              return true;
          $s=$this->getSource(true);
          return $s->contains($value);
      }
      function postValidate($value)
      {
          return true;
      }
      function hasValue()
      {
          return $this->valueSet ||
              ($this->setOnEmpty==true && $this->getEmptyValue()!==null) ||
              ($this->flags & BaseType::TYPE_SET_ON_SAVE) || ($this->flags & BaseType::TYPE_SET_ON_ACCESS);
      }
      function hasOwnValue()
      {
          return $this->valueSet;
      }
      final function copy($type)
      {
          if($type->hasValue())
              $this->_copy($type);
          else
              $this->clear();
      }
      abstract function _copy($type);

      final function equals($value)
      {
          $hasVal=$this->hasValue();
          if(!$hasVal && $value===null)
              return true;
          if(($hasVal && $value===null) || (!$hasVal && $value!==null))
              return false;
          return $this->_equals($value);
      }
      abstract function _equals($value);

      // Warning : sin validacion.Acepta cualquier cosa.Usado por los formularios cuando un campo es erroneo.
      function __rawSet($val)
      {
          if($val===null)
          {
              $this->clear();
          }
          else {
              if($this->flags & BaseType::TYPE_NOT_EDITABLE)
                  return;
              $this->_setValue($val);
          }
      }


      function is_set()
      {

          if(($this->flags & BaseType::TYPE_SET_ON_SAVE) ||
             ($this->flags & BaseType::TYPE_SET_ON_ACCESS))
              return true;
          return $this->valueSet;
      }

      function clear()
      {
          $this->valueSet=false;
      }

      function isEditable()
      {
          return !($this->flags & BaseType::TYPE_NOT_EDITABLE);
      }

      final function getValue()
      {
          if($this->valueSet)
            return $this->_getValue();
          else
          {
              if($this->setOnEmpty==true)
                  return $this->getEmptyValue();
          }
          return null;
      }
      abstract function _getValue();

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
              return null;
          $def=$this->definition["DEFAULT"];
          if($def==="null" || $def=="NULL")
              return null;
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
      function getMetaClass()
      {
          $metaClassName=$this->getMetaClassName();
          return new $metaClassName();
      }
      /*
       * La funcion getTypeFromPath sirve para obtener el tipo de un subcampo definido en algun tipo de container.
       * Es decir, no devuelve valores.Devuelve un tipo de dato, util para validar campos individuales de un formulario.
       */
      function getTypeFromPath($path)
      {
          // Un tipo basico tiene siempre que ser el ultimo elemento de un path.
          if(count($path)>0)
              throw new BaseTypeException(BaseTypeException::ERR_PATH_NOT_FOUND,["path"=>implode("/",$path)]);
          return $this;
      }
      abstract function getMetaClassName();
  }
