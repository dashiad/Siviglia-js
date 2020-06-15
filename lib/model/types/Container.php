<?php
namespace lib\model\types;
use lib\model\BaseTypedException;

class ContainerException extends \lib\model\types\BaseTypeException
{
    const ERR_REQUIRED_FIELD=101;
    const ERR_NOT_A_FIELD=102;
    const ERR_INVALID_TYPE_FOR_FIELD=103;
    const ERR_CANT_ASSIGN_FIELD_TO_NULL_CONTAINER=104;
    const TXT_REQUIRED_FIELD="Field [%field%] is required";
    const TXT_NOT_A_FIELD="Field [%field%] does not exist";
    const TXT_INVALID_TYPE_FOR_FIELD="Invalid type [%type%] for field [%field%]";
    const TXT_CANT_ASSIGN_FIELD_TO_NULL_CONTAINER="Cant assign a field on a null container";
}
class Container extends BaseType implements \ArrayAccess
{
    protected $__fields;
    protected $__fieldDef;
    protected $__stateDef;
    protected $__allowRead=false;
    protected $__changingState=false;
    protected $__isDirty=false;
    protected $__dirtyFields=array();
    protected $__pendingRequired=[];
    protected $__savedValidationMode;
    // Se declara una variable __empty, para saber si el campo esta vacio o no.
    // Un container puede que no este vacio, porque se han establecido campos, pero su valor solo va a estar a "set", si todos
    // los campos requeridos estan tambien "set".
    // Sin embargo, si tiene al menos 1 campo set, no puede considerarse vacio.Cuando al menos 1 campo esta relleno, __empty es false.
    protected $__empty;

    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        $this->__fields=[];
        $this->__fieldDef=$def["FIELDS"];
        if(isset($def["STATES"]) && $this->__stateDef==null) {

            $this->__stateDef = new \lib\model\states\StatedDefinition($this,$def);
        }

        parent::__construct($name,$def,$parentType,$value,$validationMode);

        if($this->__stateDef!==null)
            $this->__stateDef->enable();
    }

    function _setValue($val,$validationMode=null)
    {
        if($validationMode===null)
            $validationMode=$this->validationMode;

        $curDef=$this->definition["FIELDS"];
        $nSet=0;
        $self=$this;
        $assign=function($key,$value,$val) use ($self,& $nSet,$validationMode)
        {
            if(!isset($self->__fields[$key]))
                $self->__fields[$key] = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$self->fieldNamePath],$value,$self,null,$validationMode);
            $wasEmpty=true;
            if(isset($val[$key])) {
                $self->__fields[$key]->apply($val[$key],$validationMode);
                if($self->__fields[$key]->hasValue())
                    $wasEmpty=false;
            }
            if($wasEmpty==true)
            {
                if(isset($value["KEEP_KEY_ON_EMPTY"]))
                    $nSet++;
            }
            else
                $nSet++;
        };

        $stateField=null;
        if($this->__stateDef!==null)
        {
            $stateField=$this->__stateDef->getStateField();
            // Si se va a establecer el valor del campo estado, lo primero es resetear el estado que
            // tenga actualmente:
            $this->__stateDef->reset();

            // No podemos tener un objeto sin estado. Si no se especifica, se va a disparar una excepcion
            if(!isset($val[$stateField])) {
                $statusField=$this->__getField($stateField);
                if(!$statusField->hasValue())
                    throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_INVALID_STATE, array("state" => "[]"));
            }

            $assign($stateField,$this->definition["FIELDS"][$stateField],$val);
        }

        foreach($curDef as $key=>$value) {
            if($key===$stateField)
                $assign($key,$value,null);
            else
                $assign($key,$value,$val);
        }

        // Este valor es completamente dummy. Es solo para que la clase base "piense" que
        // tenemos un valor no nulo.
        // Incluso si no se ha asignado ningun campo, el valor de este container es [], no null.
            $this->value=1;

    }

    function __isEmpty()
    {
        return $this->__empty;
    }
    function setValidationMode($mode)
    {
        $this->validationMode=$mode;
        foreach($this->__fields as $k=>$v)
            $v->setValidationMode($mode);
    }

    function _validate($value)
    {
        foreach($this->definition["FIELDS"] as $key=>$type)
        {
            $curDef=$this->definition["FIELDS"][$key];
            if(!isset($this->__fields[$key]))
            {
                $this->__fields[$key] = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$this->fieldNamePath],$type,$this,null,$this->validationMode);
            }
            if($this->__fields[$key]->isRequired() && !isset($value[$key]))
                throw new BaseTypeException(BaseTypeException::ERR_REQUIRED,array("field"=>$key),$this);
            // Validamos hacia abajo solo si
            if($this->__onlyValidating==true) {
                $this->__fields[$key]->validate($value[$key]);
            }

            if($curDef["REQUIRED"] && $this->__fields[$key]->hasValue()===false)
                throw new BaseTypeException(BaseTypeException::ERR_REQUIRED,array("field"=>$key),$this);
        }
        // Finalmente, si este objeto tiene estado, se comprueba el estado.
        if($this->__stateDef!==null) {
            // Establecemos el nuevo estado en la definicion, para que el checkState funcione correctamente.
            $this->__stateDef->setNewState($this->__stateDef->getStateFieldObj()->getValue());
            $this->__stateDef->checkState();
        }

        return true;
    }
    function __sortFields()
    {
        //
    }
    function __getFields()
    {
        foreach($this->__fieldDef as $key=>$value)
            $this->__getField($key);
        return $this->__fields;
    }

    function __getField($fieldName)
    {
        if(!isset($this->__fields[$fieldName]))
        {
            if(isset($this->__fieldDef[$fieldName]))
            {
                $this->__fields[$fieldName]=TypeFactory::getType(["fieldName"=>$fieldName,"path"=>$this->fieldNamePath],$this->__fieldDef[$fieldName],$this,null,$this->validationMode);
            }
            else
            {
                // Caso de "path"
                if(strpos($fieldName,$this->getPathPrefix())!==false)
                {
                    $remField=$this->__findField($fieldName);
                    if($remField)
                        return $remField;
                }
                throw new \lib\model\BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("name"=>$fieldName));
            }
        }
        return $this->__fields[$fieldName];
    }
    function __findField($varName)
    {
        // Quitamos la primera barra, en caso de que existiera.
        if($varName[0]===$this->getPathPrefix())
        {
            $varName=substr($varName,1);
        }
        // Si ya no hay mas barras, devolvemos el nombre del campo.
        $p=strpos($varName,"/");
        if($p===false) {

            return $this->__getField($varName, true);
        }

        $parts = explode("/", $varName);
        if ($parts[0] == "") {
            array_splice($parts, 0, 1);
        }
        // Si el path es, por ejemplo, a/b/c, queremos encontrar a/b , y pedirle el campo c.
        // Por eso se extrae y se guarda el ultimo elemento.
        $lastField=array_splice($parts,-1,1);
        $result=$this->getPath($this->getPathPrefix().implode("/",$parts));
        if(!is_object($result)) {
            throw new BaseTypedException(BaseTypedException::ERR_INVALID_PATH,array("path"=>$varName));
        }
        // Por fuerza, el objeto $result tiene que ser un objeto relacion.Por lo tanto, hay que obtener el remote object, y a este, pedirle
        // el ultimo campo que hemos guardado previamente.
        if(is_a($result,'\lib\model\types\base\ModelBaseRelation')) {
            return $result->offsetGet(0)->__getField($lastField[0]);
        }
        return $result->__getField($lastField[0]);
    }
    // Este metodo devuelve el controlador de este tipo, es decir, si este container es un controller,
    // este metodo devuelve SU controller padre.
    function __getController()
    {
        return $this->__controller;
    }
    // Este metodo devuelve , si este container es controlador, ESTE objeto. Si no, el controlador
    // padre de este objeto.
    function __getControllerForChild()
    {
        if(isset($this->definition["STATES"]))
            return $this;
        return $this->__controller;
    }
    function __getFieldNames()
    {
        return array_keys($this->__fields);
    }
    function getFields()
    {
        return $this->__fields;
    }
    function  __getFieldDefinition($fieldName)
    {
        if(isset($this->__fieldDef[$fieldName]))
            return $this->__fieldDef[$fieldName];

        throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("name"=>$fieldName));

    }

    function _getValue()
    {
        /*if($this->valueSet==false) {
            $fields=array_keys($this->definition["FIELDS"]);
            if(count($fields)==0)
                return [];
            return null;
        }*/

        $curDef=$this->definition["FIELDS"];
        $nSet=0;
        $result=[];
        foreach($curDef as $key=>$value)
        {
            $field=$this->__fields[$key];
            if($field===null) continue;
            if(!$field->hasValue())
            {
                if($value["KEEP_KEY_ON_EMPTY"])
                   $result[$key]=null;
            }
            else {
                $result[$key] = $this->__fields[$key]->getValue();
                $nSet++;
            }
        }
        if($nSet==0)
        {
            if(!isset($this->definition["SET_ON_EMPTY"]) || $this->definition["SET_ON_EMPTY"]==false) {
                return null;
            }
            return [];
        }
        return $result;
    }
    function getReference()
    {
        return $this;
    }
    function _equals($value)
    {
        foreach($this->__fields as $key=>$type) {
            if(!isset($value[$key]) && $this->__fields[$key]->hasOwnValue())
                return false;
            $curDef = $this->definition["FIELDS"][$key];
            $tempType=\lib\model\types\TypeFactory::getType(
                ["fieldName"=>$key,"path"=>$this->fieldNamePath],
                $curDef,
                $this,
                $value[$key],
                \lib\model\types\BaseType::VALIDATION_MODE_NONE);
            if(!$tempType->equals($this->__fields[$key]->getValue()))
                return false;
        }
        return true;
    }

    function is_set()
    {
        return $this->valueSet;
    }

    function clear()
    {
        parent::clear();
        foreach($this->__fields as $k=>$v)
        {
            $this->__fields[$k]->clear();
        }
        // En caso de que limpiemos todo, empty es true
        $this->__empty=true;
    }

    function __toString()
    {
        return json_encode($this->getValue());
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
    function isTypeReference()
    {
        return false;
    }

    function __set($varName,$value) {

        if($this->value==null)
            throw new ContainerException(ContainerException::ERR_CANT_ASSIGN_FIELD_TO_NULL_CONTAINER);
        $this->__allowRead=true;
        if(isset($this->__fieldDef[$varName]))
        {
            if($this->__stateDef->hasState)
            {
                if(!$this->__stateDef->isEditable($varName) && $value!=$this->{$varName})
                {
                    $this->__allowRead=false;
                    throw new BaseTypedException(BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,array("field"=>$varName,"state"=>$this->__stateDef->getCurrentState()));
                }
            }
            // Ahora hay que tener cuidado.Si lo que se esta estableciendo es el campo que define el estado
            // de este objeto, no hay que copiarlo.Hay que meterlo en una variable temporal, hasta que se haga SAVE
            // del objeto.El nuevo estado aplicará a partir del SAVE.Asi, podemos cambiar otros campos que era posible
            // cambiar en el estado actual del objeto.

            $targetField=$this->__getField($varName);
            $targetField->setValue($value);
        }
        else
        {
            $remField=$this->__findField($varName);
            if($remField)
            {
                $remField->setValue($value);
            }
        }


        $this->__allowRead=false;

    }
    function hasValue(){
        if($this->value===null)
            return null;
        return $this->__isComplete();
    }
    function hasOwnValue(){
        return $this->hasValue();
    }
    function __isComplete()
    {
        $haveValue=false;
        foreach($this->__fieldDef as $k=>$v)
        {
            $f=$this->__getField($k);
            if(!$f->hasOwnValue())
            {
                if($f->isRequired())
                    return false;
                // Si no era requerido, vemos si se mantiene la key o no.
                $def=$f->getDefinition();
                if(isset($def["KEEP_KEY_ON_EMPTY"]))
                    $haveValue=true;
            }
            else
                $haveValue=true;

        }
        return $haveValue;
    }

    function __setChangingState($newState)
    {
        $this->__changingState=true;
        $this->__newState=$newState;
        $this->__pendingRequired=$this->__stateDef->getRequiredFields($newState);
        $this->__checkStateChangeCompleted();
    }
    function __checkStateChangeCompleted($field=null)
    {
        if($this->__dirtyFields==null)
            return;
        $newPending=[];
        for($k=0;$k<count($this->__pendingRequired);$k++)
        {
            $reqField=$this->__pendingRequired[$k];
            $f=$this->__getField($reqField);
            if($f->is_set())
                continue;
            if(isset($this->__dirtyFields[$reqField]))
                continue;
            $newPending[]=$reqField;
        }
        if(count($newPending)==0)
        {
            $this->__changingState=false;
            $this->__stateDef->getStateField()->onStateChangeComplete();
            $this->__stateDef->changeState($this->__newState);
        }
        $this->__pendingRequired=$newPending;
    }


    function _copy($ins)
    {
        $ins->setParent($this->parent,$this->fieldName);
        $ins->setValidationMode($this->validationMode);
        $this->apply($ins->getValue());
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Container.php");
        return '\model\reflection\Types\meta\Container';
    }

    function __get($varName)
    {
        if($this->__changingState && !$this->__allowRead)
            throw new BaseTypedException(BaseTypedException::ERR_PENDING_STATE_CHANGE,["state"=>$this->newState,"extra"=>$varName]);
        $reference=false;
        if($varName[0]=="*") {
            $reference = true;
            $varName=substr($varName,1);
        }
        if($varName[0]=="!")
        {
            $varName=substr($varName,1);
            $f=$this->__getField($varName);
            if($f->isRelation())
            {
                return $f->getRaw();
            }
        }


        $field=$this->__findField($varName);
        if($reference)
            return $field;
        if($field)
        {
            return $field->getReference();
        }

        throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("field"=>$varName));
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
        $field=array_shift($path);
        $type=$this->{"*".$field};
        return $type->getTypeFromPath($path);
    }

    public function offsetExists ( $offset ){
        return $this->value && isset($this->__fields[$offset]);
    }
    public function offsetGet ( $offset )
    {
        return $this->__get($offset);
    }
    public function offsetSet ( $offset , $value )
    {
        if(!$this->value || !isset($this->__fields[$offset]))
            return;
        return $this->__fields[$offset]->apply($value);
    }
    public function offsetUnset ( $offset ) {}

    function isDirty()
    {
        return $this->__isDirty;
    }

    function setDirty($dirty)
    {
        $this->__isDirty=$dirty;
        if(!$dirty)
            $this->__dirtyFields=[];
    }

    function addDirtyField($fieldObj)
    {
        if($this->isDirty()==false)
        $this->__setDirty(true);
        if(!in_array($fieldObj,$this->__dirtyFields))
            $this->__dirtyFields[]=$fieldObj;
        if($this->__changingState)
            $this->__checkStateChangeCompleted();
    }
    function save()
    {
        // TODO : Estamos iterando solo sobre los objetos que han sido instanciados, no sobre todos los campos..
        foreach($this->__fields as $k=>$v)
        {
            $v->save();
        }
        parent::save();
    }





    function removeDirtyField($field)
    {
        $pos=array_search($field,$this->__dirtyFields);
        if($pos!==false)
        {
            array_splice($this->__dirtyFields,$pos,1);
        }
        if(count($this->__dirtyFields)===0)
        {
            $this->__setDirty(false);
            if($this->__controller)
                $this->__controller->removeDirtyField($this);
        }
    }
    function cleanDirtyFields()
    {
        $this->__setDirty(false);
        $this->__dirtyFields=array();
        if($this->__stateDef)
            $this->__stateDef->reset();
    }
    function getDirtyFields()
    {
        return $this->__dirtyFields;
    }
    function isFieldRequired($fieldName)
    {
        $fieldDef=$this->__getField($fieldName)->getDefinition();
        if(isset($fieldDef["REQUIRED"]) && $fieldDef["REQUIRED"])
            return true;
        if($this->__stateDef!==null)
            return $this->__stateDef->isRequired($fieldName);
        return false;
    }
    /*function isEditable($fieldName)
    {
        if(!$this->__stateDef)
            return true;
        return $this->__stateDef->isEditable($fieldName);
    }*/

    function disableStateChecks()
    {
        if($this->__stateDef)
            $this->__stateDef->disable();
    }
    function enableStateChecks()
    {
        if($this->__stateDef)
            $this->__stateDef->enable();
    }
    function getStateField()
    {
        if($this->__stateDef)
            return $this->__stateDef->getStateField();
        return null;
    }
    function getStates()
    {
        if($this->__stateDef)
            return $this->__stateDef->getStates();
        return null;
    }
    function getStateDef()
    {
        if($this->definition["STATES"] && $this->__stateDef==null)
            $this->__stateDef=new \lib\model\states\StatedDefinition($this);
        return $this->__stateDef;

    }

    function getStateId($stateName)
    {
        if(!$this->__stateDef)
            return null;
        return array_search($stateName, array_keys($this->definition["STATES"]["STATES"]));
    }

    function getStateLabel($stateId)
    {
        if (!$this->__stateDef)
            return null;
        $statekeys = array_keys($this->definition["STATES"]["STATES"]);
        return $statekeys[$stateId];
    }

    function getState()
    {
        if(!$this->__stateDef)
            return null;
        return $this->__stateDef->getCurrentState();
    }
    function __getPendingRequired()
    {
        return $this->__pendingRequired;
    }


    function onModelSaved()
    {
        // LLamado cuando el modelo donde esta ese tipo, se ha almacenado. Se llama hacia abajo a todos los campos.
        // Esto se hace con todos los campos, se hayan utilizado, o no.
        foreach($this->__fieldDef as $k=>$v)
            $this->__getField($k)->onModelSaved();
    }

    // Funciones de puente con Model. Las funciones de controller
    // que solo puede ejecutar un model, se van derivando de controller a
    // controller, hasta que se alcanza un padre.
    // Problema: que el controller sea nulo.

    // Un container no tiene por que tener un serializer, pero los objetos que lo tengan como
    // controller, se lo van a pedir.
    // En la clase base, un container llama a su propio controller para obtener el serializador:
    function __getSerializer($mode="READ")
    {
        if($this->__controller==null)
            throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NO_CONTROLLER);
        return $this->__controller->__getSerializer($mode);
    }
    function __isNew()
    {
        if($this->__controller==null)
            throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NO_CONTROLLER);
        return $this->__controller->__isNew();
    }
    function __addPostSaveField($field)
    {
        if($this->__controller==null)
            throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NO_CONTROLLER);
        return $this->__controller->__addPostSaveField($field);
    }

}