<?php namespace lib\model\types;

  use lib\model\BaseTypedException;

  abstract class BaseType implements \lib\model\PathObject
  {
      protected $valueSet=false;
      protected $value=null;
      protected $__definition;
      protected $validationMode;
      protected $flags=0;
      protected $__parent;
      protected $setOnEmpty;
      protected $fieldPath;
      protected $__onlyValidating;
      protected $__isErrored=false;
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
      protected $__setFromDefault=false;
      protected $__errorException=null;
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          // Parent es el padre de este tipo, que puede ser otro tipo, o un bto.
          $this->__setParent($parentType,$name);
          // Establecemos el controller solo si el tipo derivado no lo ha hecho ya.
          // Esto lo hacen las relaciones, especialmente las inversas.
          if ($this->__parent !== null && $this->__controller==null) {
              $this->__controller = $this->__parent->__getControllerForChild();
          }
          if($this->__controller!==null) {
              $parentPath=$this->__parent->__getFieldPath();
              $this->__controllerPath = str_replace($this->__controller->__getFieldPath(), "", $this->fieldNamePath);
              $this->__controllerPath[0]=$this->__controller->__getPathPrefix();

          }

          $this->validationMode= ($validationMode==null?BaseType::VALIDATION_MODE_STRICT:$validationMode);
          // Controller es siempre el bto original,
          $this->__definition=$def;
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
                  if ($this->__hasDefaultValue() && !isset($definition["DISABLE_DEFAULT"])) {

                      $this->apply($this->__getDefaultValue(), \lib\model\types\BaseType::VALIDATION_MODE_NONE);
                      $this->__setFromDefault=true;
                  }
              } else {
                  $this->apply($value);
              }
          }
      }
     function __isSetFromDefault()
     {
         return $this->__setFromDefault;
     }
     function __getName()
     {
         return $this->__name;
     }
      function __getController()
      {
          return $this->__controller;
      }
      function __getControllerPath()
      {
          return $this->__controllerPath;
      }
      function __isAlias()
      {
          return false;
      }
      function __getFieldPath()
      {
          return $this->fieldNamePath;
      }
      function __setValidationMode($mode)
      {
          $this->validationMode=$mode;
      }
      function __getValidationMode()
      {
          return $this->validationMode;
      }
      function __setParent($parent,$name)
      {
          $this->__name=$name;
          $this->__parent=$parent;
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
              $this->fieldNamePath=$path.($fieldName!==""?"/":"").$fieldName;
          }
      }

      function __getFieldName()
      {
          return $this->fieldName;
      }
      function __getParent()
      {
          return $this->__parent;
      }
      function __hasSource()
      {
          return isset($this->__definition["SOURCE"]);
      }
      function __getSource()
      {
          return \lib\model\types\sources\SourceFactory::getSource($this,$this->__definition["SOURCE"]);
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

              $this->apply($val, $this->validationMode);

      }
      function __setDirty($dirty)
      {
          if($this->__isErrored)
          {
              $this->__clearErrored();
          }
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
      function __setErrored($exception)
      {
          $this->__isErrored=true;
          $this->__errorException=$exception;
          if($this->__controller)
          {
              $this->__controller->addErroredField($this);
          }
      }
      function __getError()
      {
          return $this->__errorException;
      }
      function __isErrored()
      {
          return $this->__isErrored;
      }
      function __clearErrored()
      {
          if($this->__isErrored==false)
              return;
          $this->__errorException=null;
          $this->__isErrored=false;
          if($this->__controller)
              $this->__controller->clearErroredField($this);
      }
      function apply($val,$validationMode=null)
      {
          $this->__setFromDefault=false;
          if($validationMode!==BaseType::VALIDATION_MODE_NONE && !$this->__isEditable()) {
            if($this->__controller && $this->__controller->getStateDef()!==null)
            $e=new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE);
            else
            $e=new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE);
            $this->__setErrored($e);
            throw $e;
        }
          $this->__onlyValidating=false;
          if($val===null || $this->__isEmptyValue($val))
          {
              if($this->value!=null) {
                  $this->__clearErrored();
                  $this->setDirty(true);
              }
              $this->value=null;
              $this->valueSet=false;
              $this->__clear();
              return;
          }
          if($validationMode===null)
              $validationMode=$this->validationMode;

          if($val===$this->__getEmptyValue())
          {
              if($this->setOnEmpty==false)
                    $val=null;
          }

              if($this->flags & BaseType::TYPE_NOT_EDITABLE)
                  return;
              $this->_setValue($val,$validationMode);
              if ($validationMode !== BaseType::VALIDATION_MODE_NONE) {
                  try {
                      $this->validate($val, $validationMode);
                      $this->__setDirty(true);
                  }catch(\Exception $e)
                  {
                      $this->__setErrored($e);
                      throw $e;
                  }
              }
              else
              {
                  if($this->__controller)
                      $this->__setDirty(false);
              }

      }
      function __getEmptyValue()
      {
          return null;
      }
      function __isEmptyValue($val)
      {
          return $val===null || $val==="";
      }
      abstract function _setValue($val,$validationMode=null);

      function validate($value,$validationMode=null)
      {
          $this->__onlyValidating=true;
          if(!$validationMode)
              $validationMode=$this->validationMode;



            if($this->__isEmptyValue($value))
            {
                if($validationMode==BaseType::VALIDATION_MODE_STRICT)
                {
                    if($this->__isRequired() ) {
                        $e=new BaseTypeException(BaseTypeException::ERR_REQUIRED, ["field" => $this->fieldPath]);
                        $this->__setErrored($e);
                        throw $e;
                    }
                }
                return true;
            }

            $res=$this->_validate($value);

            if(($validationMode==BaseType::VALIDATION_MODE_COMPLETE ||
                $validationMode==BaseType::VALIDATION_MODE_STRICT))
            {
                if(!$this->__checkSource($value)) {

                    $e=new BaseTypeException(BaseTypeException::ERR_INVALID, ["value" => $value], $this);
                    $this->__setErrored($e);
                    throw $e;
                }
            }


            $this->__onlyValidating=false;
            return $res;
      }
      function __isRequired()
      {
          if($this->__controller)
          {
              return $this->__controller->isFieldRequired($this->__controllerPath);
          }
          return $this->__isDefinedAsRequired();
      }
      function __isDefinedAsRequired()
      {
          return io($this->__definition,"REQUIRED",false);
      }
      function __checkSource($value)
      {
          if(!$this->__hasSource())
              return true;
          $s=$this->__getSource();
          return $s->contains($value);
      }
      function __postValidate($value)
      {
          return true;
      }
      function __hasValue()
      {
          return $this->valueSet || $this->setOnEmpty==true;
              //($this->setOnEmpty==true && $this->__getEmptyValue()!==null) ||
              //($this->flags & BaseType::TYPE_SET_ON_SAVE) || ($this->flags & BaseType::TYPE_SET_ON_ACCESS);
      }
      function __hasOwnValue()
      {
          return $this->valueSet;
      }
      // Este metodo es usado por Container: puede que no tenga un valor valido, porque el container no esta completo
      // (no tiene todos los campos requeridos), pero tampoco es null.
      // En el resto de los casos, es equivalente a !$this->hasValue
      function __isEmpty()
      {
          return !$this->__hasValue();
      }
      function copy($type)
      {
          if($type->__isErrored())
          {
              throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_CANT_COPY_ERRORED_FIELD);
          }
          if($type->__hasValue())
              $this->_copy($type);
          else
              $this->__clear();
      }
      abstract function _copy($type);

      final function equals($value)
      {
          $hasVal=$this->__hasValue();
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
              $this->__clear();
          }
          else {
              if($this->flags & BaseType::TYPE_NOT_EDITABLE)
                  return;
              $this->apply($val,BaseType::VALIDATION_MODE_NONE);
          }
      }

      function is_set()
      {

          if(($this->flags & BaseType::TYPE_SET_ON_SAVE) ||
             ($this->flags & BaseType::TYPE_SET_ON_ACCESS))
              return true;
          return $this->valueSet;
      }

      function __clear()
      {
          if(!$this->__isEmpty())
          {
              $this->__setDirty(true);
          }
          $this->valueSet=false;
      }

      function __isEditable()
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
          if($this->__hasValue())
            return $this->_getValue();
          else
          {
              if($this->setOnEmpty==true)
                  return $this->__getEmptyValue();
          }
          return null;
      }
      // La funcion getReference es casi equivalente a getValue. La diferencia es que está pensada
      // para ser usada con el operador ->. Mientras en una relationship, un getValue() deberia devolver
      // el entero, __getReference() devolveria el propio tipo, para utilizarlo con los operadores [] y ->
      // Cuando el tipo es simple, getReference es equivalente a getValue. Cuando el tipo es compuesto,
      // getReference devuelve el propio objeto.

      function __getReference()
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

      function __hasDefaultValue()
      {
          return isset($this->__definition["DEFAULT"]) && $this->__definition["DEFAULT"]!="NULL" && $this->__definition["DEFAULT"]!==null && $this->__definition["DEFAULT"]!=="";
      }

      function __getDefaultValue()
      {
          if(!isset($this->__definition["DEFAULT"]))
              return null;
          $def=$this->__definition["DEFAULT"];
          if($def==="null" || $def=="NULL")
              return null;
          return $this->__definition["DEFAULT"];
      }
      function __getRelationshipType($name,$parent)
      {
          return \lib\model\types\TypeFactory::getType($name,$this->__definition,$parent);
      }
      function getDefinition()
      {
          if(!isset($this->__definition["TYPE"]))
          {
              $parts=explode("\\",get_class($this));
              $this->__definition["TYPE"]=$parts[count($parts)-1];
          }
          return $this->__definition;
      }

      /*
       * La funcion getTypeFromPath sirve para obtener el tipo de un subcampo definido en algun tipo de container.
       * Es decir, no devuelve valores.Devuelve un tipo de dato, util para validar campos individuales de un formulario.
       */
      function __getTypeFromPath($path)
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
          $ctx = new \lib\model\BaseObjectContext($this, $this->__getPathPrefix(), $ctxStack);
          $path=new \lib\model\PathResolver($ctxStack,$path);
          return $path->getPath();
      }
      function __getPathPrefix()
      {
          return "#";
      }


      function summarize()
      {
          $req=io($this->__definition,"REQUIRED",null);
          if($req==null)
            $req=false;
          else
          {
              if($req!=true && $req!="true")
                  $req=false;

          }
          return [
            "name"=>$this->fieldName,
            "def"=>$this->__definition,
            "required"=>$req,
            "path"=>$this->__getFieldPath(),
            "hasDefault"=>$this->__hasDefaultValue(),
            "default"=>$this->__getDefaultValue(),
            "source"=>$this->__getSource(),
            "hasSource"=>$this->__hasSource(),
            "value"=>$this->getValue(),
            "hasValue"=>$this->valueSet
          ];
      }
      function save()
      {
        // Por defecto, un tipo, cuando se guarda,
          // si tenia un controller, se borra del controller como dirtyField.
          if($this->__isErrored())
              throw new BaseTypedException(BaseTypedException::ERR_CANT_SAVE_ERRORED_FIELD);
          if($this->isDirty())
              $this->__setDirty(false);
      }
      function __onModelSaved()
      {
          // LLamado cuando el controller se guarda.Por defecto, no hace nada.
      }
      // En un tipo que no sea container, tanto el container "upstream", como el container "downstream",
      // es el mismo: el __controller que tenga asignado este objeto.

      function __getControllerForChild()
      {
          return $this->__controller;
      }
      function __isRelation()
      {
          return false;
      }
      function getTypes()
      {
          return array($this->fieldName=>$this);
      }


  }
