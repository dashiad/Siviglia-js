<?php
namespace lib\model\types;
class ContainerException extends \lib\model\types\BaseTypeException
{
    const ERR_REQUIRED_FIELD=101;
    const ERR_NOT_A_FIELD=102;
    const ERR_INVALID_TYPE_FOR_FIELD=103;
    const TXT_REQUIRED_FIELD="Field [%field%] is required";
    const TXT_NOT_A_FIELD="Field [%field%] does not exist";
    const TXT_INVALID_TYPE_FOR_FIELD="Invalid type [%type%] for field [%field%]";
}
class Container extends BaseContainer implements \ArrayAccess
{
    var $__fields;
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        $this->__fields=[];
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
    function __set($fieldName,$value)
    {
        if(!isset($this->definition["FIELDS"][$fieldName]))
            throw new BaseTypeException(\lib\model\types\ContainerException::ERR_NOT_A_FIELD,array("field"=>$fieldName),$this);
        $instance=\lib\model\types\TypeFactory::getType($fieldName,$this->definition["FIELDS"][$fieldName],$this,null,$this->validationMode);

        if(is_object($value))
        {
            if(get_class($value)!=get_class($instance))
                throw new ContainerException(ContainerException::ERR_INVALID_TYPE_FOR_FIELD,["type"=>get_class($value),"field"=>$fieldName],$this);

            $instance->validate($value->getValue());
            $this->__fields[$fieldName]=$value;
        }
        else
        {
            $instance->apply($value);
            $this->__fields[$fieldName]=$instance;
        }
        if($this->__fields[$fieldName]->hasOwnValue())
            $this->valueSet=true;
    }
    function __get($fieldName)
    {
        // Si se esta validando,lo que hacemos es devolver un campo "temporal". Asi, es posible
        // validar SOURCES, que hacen referencia al valor de otros campos, mientra se está
        // validando, y esos campos aun no han sido asignados.

        $returnType=false;
        if($fieldName[0]=="*") {
            $returnType=true;
            $fieldName = substr($fieldName, 1);
        }

            if (!isset($this->__fields[$fieldName])) {
                if (!isset($this->definition["FIELDS"][$fieldName]))
                    throw new ContainerException(ContainerException::ERR_NOT_A_FIELD, ["field" => $fieldName], $this);
                else
                    $this->__fields[$fieldName] = \lib\model\types\TypeFactory::getType($fieldName, $this->definition["FIELDS"][$fieldName],$this,null,$this->validationMode);

            }
            $target=$this->__fields[$fieldName];

            if($returnType)
                return $target;

        return $target->getValue();
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
}
