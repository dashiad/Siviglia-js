<?php namespace lib\model\types;

  abstract class BaseType
  {
      var $valueSet=false;
      var $value;
      var $definition;
      var $flags=0;
      var $typeReference;
      var $referencedModel;
      var $referencedField;
      var $parent;
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
          if($this->hasDefaultValue())
              $this->__rawSet($this->getDefaultValue());
          if(isset($def["FIXED"])) {
              $this->__rawSet($def["FIXED"]);
              $this->flags|=BaseType::TYPE_NOT_EDITABLE;
          }

      }
      function setParent($parent)
      {
          $this->parent=$parent;
      }
      function hasSource()
      {
          return isset($this->definition["SOURCE"]);
      }
      function getSource()
      {
          return \lib\model\types\sources\SourceFactory::getSource($this->definition["SOURCE"]);
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
      abstract function _setValue($val);

      final function validate($value)
      {
            if($value===null)
                return true;
            return $this->_validate($value);
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

          if(!($this->flags & BaseType::TYPE_SET_ON_SAVE) &&
             !($this->flags & BaseType::TYPE_SET_ON_ACCESS))
              return false;
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
      function setTypeReference($model,$field)
      {
          $this->referencedModel=$model;
          $this->referencedField=$field;
          $this->typeReference=true;
      }
      abstract function getMetaClassName();
  }