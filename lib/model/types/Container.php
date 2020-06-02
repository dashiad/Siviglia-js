<?php
namespace lib\model\types;
use lib\model\BaseTypedException;

class ContainerException extends \lib\model\types\BaseTypeException
{
    const ERR_REQUIRED_FIELD=101;
    const ERR_NOT_A_FIELD=102;
    const ERR_INVALID_TYPE_FOR_FIELD=103;
    const TXT_REQUIRED_FIELD="Field [%field%] is required";
    const TXT_NOT_A_FIELD="Field [%field%] does not exist";
    const TXT_INVALID_TYPE_FOR_FIELD="Invalid type [%type%] for field [%field%]";
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

    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        $this->__fields=[];
        $this->__fieldDef=$def["FIELDS"];
        if($def["STATES"])
        {
            $this->__stateDef=new \lib\model\StatedDefinition($this);
        }
        else
            $this->stateDef=null;
        parent::__construct($name,$def,$parentType,$value,$validationMode);

    }
    function _setValue($val,$validationMode=null)
    {
        if($validationMode===null)
            $validationMode=$this->validationMode;

        $curDef=$this->definition["FIELDS"];
        $nSet=0;
        foreach($curDef as $key=>$value) {
            $this->__fields[$key] = \lib\model\types\TypeFactory::getType($key,$value,$this,null,$validationMode);
            $wasEmpty=true;
            if(isset($val[$key])) {
                $this->__fields[$key]->apply($val[$key],$validationMode);
                if($this->__fields[$key]->hasValue())
                    $wasEmpty=false;
            }
            if($wasEmpty==true)
            {
                if(isset($value["DEFAULT"]))
                    $this->__fields[$key]->__rawSet($value["DEFAULT"]);
                else
                {
                    if(isset($value["REQUIRED"]))
                        throw new ContainerException(ContainerException::ERR_REQUIRED_FIELD,["field"=>$key],$this);
                    if(isset($value["KEEP_KEY_ON_EMPTY"]))
                        $nSet++;
                }
            }
            else
                $nSet++;
        }
        if($nSet>0)
            $this->valueSet=true;
        else
        {
            if(isset($this->definition["SET_ON_EMPTY"]) && $this->definition["SET_ON_EMPTY"]==true) {
                $this->valueSet=true;
            }
        }
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
                $this->__fields[$key] = \lib\model\types\TypeFactory::getType($key,$type,$this,null,$this->validationMode);
            }
            if(!isset($this->__fields[$key]) && isset($curDef["REQUIRED"]) && $curDef["REQUIRED"]!=false)
                throw new ContainerException(ContainerException::ERR_REQUIRED_FIELD,array("field"=>$key),$this);
            $this->__fields[$key]->validate($value[$key]);
            if($curDef["REQUIRED"] && $this->__fields[$key]->hasValue()===false)
                throw new ContainerException(ContainerException::ERR_REQUIRED_FIELD,array("field"=>$key),$this);
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
                $this->__fields[$fieldName]=TypeFactory::getType($fieldName,$this->__fieldDef["fieldName"],$this,null,$this->validationMode);
                if($this->__stateDef===null) {
                    if($this->controller)
                        $this->__fields[$fieldName]->setController($this->controller);
                }
                else
                    $this->__fields[$fieldName]->setController($this);
            }
            else
            {
                // Caso de "path"
                if(strpos($fieldName,"/")!==false)
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
        $result=$this->getPath("/".implode("/",$parts));
        if(!is_object($result)) {
            throw new BaseTypedException(BaseTypedException::ERR_INVALID_PATH,array("path"=>$varName));
        }
        // Por fuerza, el objeto $result tiene que ser un objeto relacion.Por lo tanto, hay que obtener el remote object, y a este, pedirle
        // el ultimo campo que hemos guardado previamente.
        if(is_a($result,'\lib\model\types\ModelBaseRelation')) {
            return $result->offsetGet(0)->__getField($lastField[0]);
        }
        return $result->__getField($lastField[0]);
    }
    function __getFieldNames()
    {
        return array_keys($this->__fields);
    }
    function getFields()
    {
        return $this->__fields;
    }
    function & __getFieldDefinition($fieldName)
    {
        if(isset($this->__fieldDef[$fieldName]))
            return $this->__fieldDef[$fieldName];

        throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("name"=>$fieldName));

    }

    function _getValue()
    {
        // Si la definicion no tiene campos, el valor es [].
        // Supongamos el tipo email. Deriva de String. Si se pone en
        // un typeswitcher, email no tendria ningun campo extra, asi que
        // su definicion de tipo seria un container vacio.
        if($this->valueSet==false) {
            $fields=array_keys($this->definition["FIELDS"]);
            if(count($fields)==0)
                return [];
            return null;
        }

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

    function hasValue()
    {
        return $this->valueSet;
    }
    function hasOwnValue()
    {
        return $this->valueSet;
    }
    function _equals($value)
    {
        foreach($this->__fields as $key=>$type) {
            if(!isset($value[$key]) && $this->__fields[$key]->hasOwnValue())
                return false;
            $curDef = $this->definition["FIELDS"][$key];
            $tempType=\lib\model\types\TypeFactory::getType(
                $key,
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
        $this->__fields=[];
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
        }
        else
        {
            $remField=$this->__findField($varName);
            if($remField)
            {
                $remField->setValue($value);
            }
        }

        // Ahora hay que tener cuidado.Si lo que se esta estableciendo es el campo que define el estado
        // de este objeto, no hay que copiarlo.Hay que meterlo en una variable temporal, hasta que se haga SAVE
        // del objeto.El nuevo estado aplicará a partir del SAVE.Asi, podemos cambiar otros campos que era posible
        // cambiar en el estado actual del objeto.

        $targetField=$this->__getField($varName);
        $targetField->setValue($value);
        if($targetField->parent==$this)
            $this->addDirtyField($varName);
        $this->__allowRead=false;
        if($this->__fields[$varName]->hasOwnValue())
            $this->valueSet=true;
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
    function getEmptyValue()
    {
        return [];
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
        $field=$this->__findField($varName);
        if($reference)
            return $field;
        if($field)
        {
            return $field->getValue();
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
            $this->__dirtyFields=array();
    }

    function addDirtyField($fieldName)
    {
        $this->__isDirty=true;
        $this->__dirtyFields[$fieldName]=1;
        if($this->__changingState)
            $this->__checkStateChangeCompleted();
    }

    function cleanDirtyFields()
    {
        $this->__isDirty=false;
        $this->__dirtyFields=array();
        $this->__stateDef->reset();
    }
    function getDirtyFields()
    {
        return $this->__dirtyFields;
    }
    function isRequired($fieldName)
    {
        $fieldDef=$this->__getField($fieldName)->getDefinition();
        // TODO: El modelo podria ser otro, no solo el actual.
        if(isset($fieldDef["MODEL"]) && isset($fieldDef["FIELD"]))
            $fieldName=$fieldDef["FIELD"];
        return $this->__stateDef->isRequired($fieldName);
    }
    function isEditable($fieldName)
    {
        if(!$this->__stateDef)
            return true;
        return $this->__stateDef->isEditable($fieldName);
    }

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
        if(!$this->stateDef)
            return null;
        return $this->__stateDef->getCurrentState();
    }

}
