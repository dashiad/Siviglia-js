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
    protected $__savedValidationMode;
    protected $__errored=[];

    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        $this->__fields=[];
        $this->__errored=[];
        if(isset($def["INHERITS"]))
        {
            $baseType=\lib\model\types\TypeFactory::getType("inh",["TYPE"=>$def["INHERITS"]],null);
            $baseDef=$baseType->getDefinition();
            foreach($def as $k=>$v)
            {
                if($k=="FIELDS") {
                    foreach ($def["FIELDS"] as $k1 => $v1) {
                        $baseDef["FIELDS"][$k1] = $v1;
                    }
                }
                else
                    $baseDef[$k]=$v;
            }
            $def=$baseDef;
        }
        $this->__fieldDef=$def["FIELDS"];
        if(isset($def["STATES"]) && $this->__stateDef==null) {

            $this->__stateDef = new \lib\model\states\StatedDefinition($this,$def);
        }

        parent::__construct($name,$def,$parentType,$value,$validationMode);

        if($this->__stateDef!==null)
            $this->__stateDef->enable();
    }

    function reset()
    {
        if ($this->__isDirty) {
            $this->__setDirty(false);
        }
        $this->__dirtyFields = [];
        $this->__clearErrored();

        foreach($this->__fields as $k=>$v) {
            unset($this->__fields[$k]);
        }
    }
    function __clearErrored()
    {
        if($this->__isErrored==false && count($this->__errored) === 0)
            return;
        $this->__errored = [];
        $this->__errorException=null;
        $this->__isErrored=false;
        if($this->__controller)
            $this->__controller->clearErroredField($this);
    }

    function _setValue($val,$validationMode=null)
    {
        if($validationMode===null)
            $validationMode=$this->validationMode;

        $this->reset();
        $curDef=$this->__definition["FIELDS"];
        $self=$this;
        $assign=function($key,$value,$val) use ($self,$validationMode)
        {
            if(!isset($self->__fields[$key]))
                $self->__fields[$key] = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$self->fieldNamePath],$value,$self,null,$validationMode);
            if(isset($val[$key])) {
                $self->__fields[$key]->apply($val[$key],$validationMode);
            }
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
                if(!$statusField->__hasValue())
                    throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_INVALID_STATE, array("state" => "[]"));
            }

            $assign($stateField,$this->__definition["FIELDS"][$stateField],$val);
        }

        foreach($curDef as $key=>$value) {
            //if($key===$stateField)
            //    $assign($key,$value,null);
            //else
            if($key!==$stateField)
                $assign($key,$value,$val);
        }

        // Este valor es completamente dummy. Es solo para que la clase base "piense" que
        // tenemos un valor no nulo.
        // Incluso si no se ha asignado ningun campo, el valor de este container es [], no null.
            $this->value=1;

    }


    function __setValidationMode($mode)
    {
        $this->validationMode=$mode;
        foreach($this->__fields as $k=>$v)
            $v->__setValidationMode($mode);
    }

    function _validate($value)
    {
        foreach($this->__definition["FIELDS"] as $key=>$type)
        {
            $curDef=$this->__definition["FIELDS"][$key];
            if(!isset($this->__fields[$key]))
            {
                $this->__fields[$key] = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$this->fieldNamePath],$type,$this,null,$this->validationMode);
            }
            if($this->__fields[$key]->__isRequired() && $this->__fields[$key]->__hasValue()===false) {
                $e=new BaseTypeException(BaseTypeException::ERR_REQUIRED, array("field" => $key), $this);
                $this->__fields[$key]->__setErrored($e);
                throw $e;

            }
            // Validamos hacia abajo solo si
            if($this->__onlyValidating==true) {
                $this->__fields[$key]->validate($value[$key]);
            }
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
                if(strpos($fieldName,$this->__getPathPrefix())!==false)
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
        if($varName[0]===$this->__getPathPrefix())
        {
            $varName=substr($varName,1);
        }
        // Si ya no hay mas barras, devolvemos el nombre del campo.
        $parts = explode("/", $varName);
        if(count($parts)==1) {

            return $this->__getField($varName, true);
        }
        if ($parts[0] == "") {
            array_splice($parts, 0, 1);
        }
        // Si el path es, por ejemplo, a/b/c, queremos encontrar a/b , y pedirle el campo c.
        // Por eso se extrae y se guarda el ultimo elemento.
        $lastField=array_splice($parts,-1,1);
        $result=$this->getPath($this->__getPathPrefix().implode("/",$parts));
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
        if(isset($this->__definition["STATES"]))
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
            $fields=array_keys($this->__definition["FIELDS"]);
            if(count($fields)==0)
                return [];
            return null;
        }*/

        $curDef=$this->__definition["FIELDS"];
        $nSet=0;
        $result=[];
        foreach($curDef as $key=>$value)
        {
            $field=$this->__fields[$key];
            if($field===null) continue;
            if(!$field->__hasValue())
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
            if(!isset($this->__definition["SET_ON_EMPTY"]) || $this->__definition["SET_ON_EMPTY"]==false) {
                return null;
            }
            return [];
        }
        return $result;
    }
    function __getReference()
    {
        return $this;
    }
    function _equals($value)
    {
        foreach($this->__fields as $key=>$type) {
            if(!isset($value[$key]) && $this->__fields[$key]->__hasOwnValue())
                return false;
            $curDef = $this->__definition["FIELDS"][$key];
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

    function __clear()
    {
        parent::__clear();
        foreach($this->__fields as $k=>$v)
        {
            $this->__fields[$k]->__clear();
        }
    }

    function __toString()
    {
        return json_encode($this->getValue());
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
    function isTypeReference()
    {
        return false;
    }
    // Un container no tiene errores propios.
    function __setErrored($exception)
    {
        return;
    }

    function __set($varName,$value) {

        if($this->value==null)
            throw new ContainerException(ContainerException::ERR_CANT_ASSIGN_FIELD_TO_NULL_CONTAINER);


        $this->__allowRead=true;
        if(isset($this->__fieldDef[$varName]))
        {
            // Que yo sepa, esto lo hace el setValue de los campos..No hace falta volver a
            // hacerlo aqui...
           /* if($this->__stateDef && $this->__stateDef->hasState)
            {
                if(!$this->__stateDef->isEditable($varName) && $value!=$this->{$varName})
                {
                    $this->__allowRead=false;
                    $exception=new BaseTypedException(BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,array("field"=>$varName,"state"=>$this->__stateDef->getCurrentState()));
                    $this->{"*".$varName}->__setErrored($exception);
                    throw $exception;
                }
            }*/
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
        $this->__setFromDefault=false;


        $this->__allowRead=false;

    }
    function __hasValue(){
        if($this->value===null)
            return false;
        return $this->__isComplete();
    }
    function __hasOwnValue(){
        return $this->__hasValue();
    }
    function __isComplete($markErrored=false)
    {
        $haveValue=false;
        $nDefaults=0;
        $nSet=0;
        foreach($this->__fieldDef as $k=>$v)
        {
            $f=$this->__getField($k);
            if(!$f->__hasOwnValue())
            {
                if($this->isFieldRequired($k)) {
                    if($markErrored)
                    {
                        $e=new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD,["field"=>$f->__getFieldName()]);
                    }
                    return false;
                }
                // Si no era requerido, vemos si se mantiene la key o no.
                $def=$f->getDefinition();
                if(isset($def["KEEP_KEY_ON_EMPTY"]))
                    $haveValue=true;
            }
            else {
                if(!$f->__isSetFromDefault())
                    $haveValue = true;
            }

        }
        return $haveValue;
    }

    function __setChangingState($newState)
    {
        $this->__changingState=true;
        $this->__newState=$newState;
        $this->__checkStateChangeCompleted();
    }
    function __checkStateChangeCompleted($field=null)
    {
        if(!$this->__stateDef)
            return true;
        if($this->__dirtyFields==null)
            return;
        return $this->__isComplete();
    }


    function _copy($ins)
    {
        $this->__setParent($ins->__parent,$ins->__name);
        $this->__setValidationMode($ins->validationMode);
        $this->apply($ins->getValue());
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
            if($f->__isRelation())
            {
                return $f->getRaw();
            }
        }


        $field=$this->__findField($varName);
        if($reference)
            return $field;
        if($field)
        {
            return $field->__getReference();
        }

        throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("field"=>$varName));
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
        $field=array_shift($path);
        $type=$this->{"*".$field};
        return $type->__getTypeFromPath($path);
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
        $fieldName=$fieldObj->__getFieldName();
        unset($this->__errored[$fieldName]);
        if($this->isDirty()==false) {
            if(count($this->__errored)==0)
                $this->__setDirty(true);
        }

        if(!in_array($fieldObj,$this->__dirtyFields))
            $this->__dirtyFields[]=$fieldObj;
        if($this->__changingState)
            $this->__checkStateChangeCompleted();
    }
    function addErroredField($fieldObj)
    {
        $this->__errored[$fieldObj->__getFieldPath()]=$fieldObj;
        if($this->__controller)
            $this->__controller->addErroredField($this);
    }
    function getErroredFields()
    {
        return array_values($this->__errored);
    }
    function clearErroredField($fieldObj)
    {
        if(isset($this->__errored[$fieldObj->__getFieldPath()])) {
            unset($this->__errored[$fieldObj->__getFieldPath()]);

            if (count($this->__errored) == 0) {
                if($this->__controller)
                    $this->__controller->clearErroredField($this);
                if (count($this->__dirtyFields) > 0)
                    $this->setDirty(true);
            }
        }
    }
    function save()
    {
        if(count($this->__errored)>0)
            throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_CANT_SAVE_ERRORED_FIELD,["field"=>$this->__getFieldPath()]);
        // TODO : Estamos iterando solo sobre los objetos que han sido instanciados, no sobre todos los campos..


        foreach($this->__fields as $k=>$v)
        {
            $v->save();
        }

        $this->__isComplete(true);
        if($this->__stateDef)
            $this->__stateDef->checkState();
        // Volvemos a chequear despues del save de los objetos, por si acaso.
        if(count($this->__errored)>0)
            throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_CANT_SAVE_ERRORED_FIELD,["field"=>$this->__getFieldPath()]);
        parent::save();
    }
    function __isErrored()
    {
        return $this->__isErrored || count($this->__errored)>0;
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
        $field=$this->__getField($fieldName);
        if($field->__isDefinedAsRequired())
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
        return $this->__stateDef;
    }

    function getStateId($stateName)
    {
        if(!$this->__stateDef)
            return null;
        return array_search($stateName, array_keys($this->__definition["STATES"]["STATES"]));
    }

    function getStateLabel($stateId)
    {
        if (!$this->__stateDef)
            return null;
        $statekeys = array_keys($this->__definition["STATES"]["STATES"]);
        return $statekeys[$stateId];
    }

    function getState()
    {
        if(!$this->__stateDef)
            return null;
        return $this->__stateDef->getCurrentState();
    }



    function __onModelSaved()
    {
        // LLamado cuando el modelo donde esta ese tipo, se ha almacenado. Se llama hacia abajo a todos los campos.
        // Esto se hace con todos los campos, se hayan utilizado, o no.
        foreach($this->__fieldDef as $k=>$v)
            $this->__getField($k)->__onModelSaved();
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