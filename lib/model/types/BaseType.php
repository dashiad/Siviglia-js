<?php namespace lib\model\types;

  use lib\model\BaseTypedException;

  abstract class BaseType implements \lib\model\PathObject
  {
      protected $valueSet=false;
      protected $value=null;
      protected $definition;
      protected $validationMode;
      protected $flags=0;
      protected $parent;
      protected $setOnEmpty;
      protected $fieldPath;
      protected $__onlyValidating;
      const TYPE_SET_ON_SAVE=0x1;
      const TYPE_SET_ON_ACCESS=0x2;
      const TYPE_IS_FILE=0x4;
      const TYPE_REQUIRES_SAVE=0x8;
      const TYPE_NOT_EDITABLE=0x10;
      const TYPE_NOT_MODIFIED_ON_NULL=0x20;
      const TYPE_REQUIRES_UPDATE_ON_NEW=0x40;

      const VALIDATION_MODE_NONE=0;  // Sin validacion alguna.
      const VALIDATION_MODE_SIMPLE=1; // Validaciones simples de tipo (__validate())
      const VALIDATION_MODE_COMPLETE=2; // Validacion de tipo y source.
      const VALIDATION_MODE_STRICT=3; // Validacion de tipo, source y REQUIRED

      protected $fieldName;
      protected $fieldNamePath;
      // El controller es el container que gestiona los posibles cambios de estado.
      protected $__controller=null;
      protected $__controllerPath=null;
      protected $__isDirty=false;
      protected $__name;
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          // Parent es el padre de este tipo, que puede ser otro tipo, o un bto.
          $this->__name=$name;
          $this->parent=$parentType;
          if($name!==null) {
              if(is_string($name))
              {
                  $path="";
                  $fieldName=$name;
              }
              else {
                  $path=isset($name["path"])?($name["path"]=="/"?"":$name["path"]):"";
                  $fieldName=$name["fieldName"];
              }
              $this->fieldName=$fieldName;
              $this->fieldNamePath=$path."/".$fieldName;
          }
          // Establecemos el controller solo si el tipo derivado no lo ha hecho ya.
          // Esto lo hacen las relaciones, especialmente las inversas.
          if ($this->parent !== null && $this->__controller==null) {
              $this->__controller = $this->parent->__getControllerForChild();
          }
          if($this->__controller!==null) {
              $parentPath=$this->parent->__getFieldPath();
              $this->__controllerPath = str_replace($this->__controller->__getFieldPath(), "", $this->fieldNamePath);
              $this->__controllerPath[0]=$this->__controller->getPathPrefix();

          }

          $this->validationMode= ($validationMode==null?BaseType::VALIDATION_MODE_STRICT:$validationMode);
          // Controller es siempre el bto original,
          $this->definition=$def;
          $this->flags=0;
          $this->setOnEmpty=false;
          $this->__onlyValidating=false;
          if(isset($def["SET_ON_EMPTY"]) && $def["SET_ON_EMPTY"]==true)
              $this->setOnEmpty=true;
          if (isset($def["FIXED"])) {
              $this->__rawSet($def["FIXED"]);
              $this->flags |= BaseType::TYPE_NOT_EDITABLE;
          }
          else {
              if ($value === null) {
                  if ($this->hasDefaultValue() && !isset($definition["DISABLE_DEFAULT"]))
                      $this->apply($this->getDefaultValue(),\lib\model\types\BaseType::VALIDATION_MODE_NONE);
              } else {
                  $this->apply($value);
              }
          }
      }
     function __getName()
     {
         return $this->__name;
     }
      function getController()
      {
          return $this->__controller;
      }
      function isAlias()
      {
          return false;
      }
      function __getFieldPath()
      {
          return $this->fieldNamePath;
      }
      function setValidationMode($mode)
      {
          $this->validationMode=$mode;
      }
      function getValidationMode()
      {
          return $this->validationMode;
      }
      function setParent($parent,$fieldName)
      {
          $this->parent=$parent;
          $this->fieldName=$fieldName;
      }
      function getFullPath()
      {
          if(!isset($this->fieldPath))
          {
            $parts=[$this->fieldName];
            $cur=$this->parent;
            $n=0;
            while(!is_a($cur,'\lib\model\ModelField') && $cur)
            {
                $n++;
                if($n>20)
                die("<h1>".$n."</h1>");

                  $parts[]=$cur->getFieldName();
                  $cur=$cur->getParent();
            }
            $this->fieldPath="/".implode("/",array_reverse($parts));

        }
        return $this->fieldPath;
      }
      function getFieldName()
      {
          return $this->fieldName;
      }
      function getParent()
      {
          return $this->parent;
      }
      function hasSource()
      {
          return isset($this->definition["SOURCE"]);
      }
      function getSource()
      {
          return \lib\model\types\sources\SourceFactory::getSource($this,$this->definition["SOURCE"]);
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
          if($this->isEditable()) {
              $this->apply($val, $this->validationMode);
              $this->__setDirty(true);
          }
          else {
              if($this->__controller && $this->__controller->getStateDef()!==null)
                throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE);
              else
                  throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE);

          }
      }
      function __setDirty($dirty)
      {
          if($dirty==$this->__isDirty)
              return;
          if($this->__controller) {
              if($dirty)
                $this->__controller->addDirtyField($this);
              else
                  $this->__controller->removeDirtyField($this);
          }
          $this->__isDirty=$dirty;
          // Si un campo esta sucio, tiene valor...(¿?)
      }
      function isDirty()
      {
          return $this->__isDirty;
      }

      function apply($val,$validationMode=null)
      {
          $this->__onlyValidating=false;
          if($val===null || $this->isEmptyValue($val))
          {
              if($this->value!=null)
                  $this->setDirty(true);
              $this->value=null;
              $this->valueSet=false;
              $this->clear();
              return;
          }
          if($validationMode===null)
              $validationMode=$this->validationMode;

          if($val===$this->getEmptyValue())
          {
              if($this->setOnEmpty==false)
                    $val=null;
          }

              if($this->flags & BaseType::TYPE_NOT_EDITABLE)
                  return;
              $this->_setValue($val,$validationMode);
              if ($validationMode !== BaseType::VALIDATION_MODE_NONE) {
                  $this->validate($val, $this->validationMode);
                  $this->__setDirty(true);
              }
              else
              {
                  if($this->__controller)
                      $this->__setDirty(false);
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
      abstract function _setValue($val,$validationMode=null);

      function validate($value,$validationMode=null)
      {
          $this->__onlyValidating=true;
          if(!$validationMode)
              $validationMode=$this->validationMode;

            if($value===null)
                return true;
            $res=$this->_validate($value);

            if(($validationMode==BaseType::VALIDATION_MODE_COMPLETE ||
                $validationMode==BaseType::VALIDATION_MODE_STRICT))
            {
                if(!$this->checkSource($value))
                throw new BaseTypeException(BaseTypeException::ERR_INVALID,["value"=>$value],$this);
            }

            if($validationMode==BaseType::VALIDATION_MODE_STRICT)
            {
               $req=$this->isRequired();
                if($req && $this->isEmptyValue($value))
                    throw new BaseTypeException(BaseTypeException::ERR_REQUIRED,["field"=>$this->fieldPath]);
            }
            $this->__onlyValidating=false;
            return $res;
      }
      function isRequired()
      {
          if($this->__controller)
          {
              return $this->__controller->isFieldRequired($this->__controllerPath);
          }
          return $this->isDefinedAsRequired();
      }
      function isDefinedAsRequired()
      {
          return io($this->definition,"REQUIRED",false);
      }
      function checkSource($value)
      {
          if(!$this->hasSource())
              return true;
          $s=$this->getSource();
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
      // Este metodo es usado por Container: puede que no tenga un valor valido, porque el container no esta completo
      // (no tiene todos los campos requeridos), pero tampoco es null.
      // En el resto de los casos, es equivalente a !$this->hasValue
      function __isEmpty()
      {
          return !$this->hasValue();
      }
      function copy($type)
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
          if(!$this->__isEmpty())
          {
              $this->__setDirty(true);
          }
          $this->valueSet=false;
      }

      function isEditable()
      {
          if ($this->flags & BaseType::TYPE_NOT_EDITABLE)
              return false;
          if(!$this->__controller)
              return true;
          if(!$this->__controller->getStateDef())
              return true;
          return $this->__controller->getStateDef()->isEditable($this->__controllerPath);
      }

      function getValue()
      {
          if($this->hasValue())
            return $this->_getValue();
          else
          {
              if($this->setOnEmpty==true)
                  return $this->getEmptyValue();
          }
          return null;
      }
      // La funcion getReference es casi equivalente a getValue. La diferencia es que está pensada
      // para ser usada con el operador ->. Mientras en una relationship, un getValue() deberia devolver
      // el entero, getReference() devolveria el propio tipo, para utilizarlo con los operadores [] y ->
      // Cuando el tipo es simple, getReference es equivalente a getValue. Cuando el tipo es compuesto,
      // getReference devuelve el propio objeto.

      function getReference()
      {
          return $this->getValue();
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
      function getRelationshipType($name,$parent)
      {
          return \lib\model\types\TypeFactory::getType($name,$this->definition,$parent);
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
              throw new BaseTypeException(BaseTypeException::ERR_PATH_NOT_FOUND,["path"=>implode("/",$path)],$this);
          return $this;
      }
      /*
       *  getPath "canonico", usando context,etc,etc
       *  La posicion actual del basetypedobject, es el contexto "#"
       */
      function getPath($path,$ctxStack=null)
      {
          if($ctxStack===null) {
              $ctxStack = new \lib\model\ContextStack();
          }
          $ctx = new \lib\model\BaseObjectContext($this, $this->getPathPrefix(), $ctxStack);
          $path=new \lib\model\PathResolver($ctxStack,$path);
          return $path->getPath();
      }
      function getPathPrefix()
      {
          return "#";
      }


      function summarize()
      {
          $req=io($this->definition,"REQUIRED",null);
          if($req==null)
            $req=false;
          else
          {
              if($req!=true && $req!="true")
                  $req=false;

          }
          return [
            "name"=>$this->fieldName,
            "def"=>$this->definition,
            "required"=>$req,
            "path"=>$this->getFullPath(),
            "hasDefault"=>$this->hasDefaultValue(),
            "default"=>$this->getDefaultValue(),
            "source"=>$this->getSource(),
            "hasSource"=>$this->hasSource(),
            "value"=>$this->getValue(),
            "hasValue"=>$this->valueSet
          ];
      }
      function save()
      {
        // Por defecto, un tipo, cuando se guarda,
          // si tenia un controller, se borra del controller como dirtyField.
          if($this->isDirty())
              $this->__setDirty(false);
      }
      function onModelSaved()
      {
          // LLamado cuando el controller se guarda.Por defecto, no hace nada.
      }
      // En un tipo que no sea container, tanto el container "upstream", como el container "downstream",
      // es el mismo: el __controller que tenga asignado este objeto.
      function __getController()
      {
          return $this->__controller;
      }
      function __getControllerForChild()
      {
          return $this->__controller;
      }
      function isRelation()
      {
          return false;
      }
      function getTypes()
      {
          return array($this->fieldName=>$this);
      }


  }
