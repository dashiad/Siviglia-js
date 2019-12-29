<?php
namespace model\reflection\Types\meta;
class ContainerException extends \model\reflection\Types\meta\BaseTypeException
{
    const ERR_REQUIRED_FIELD=101;
    const ERR_NOT_A_FIELD=102;
    const ERR_INVALID_TYPE_FOR_FIELD=103;
    const TXT_REQUIRED_FIELD="Field [%field%] is required";
    const TXT_NOT_A_FIELD="Field [%field%] does not exist";
    const TXT_INVALID_TYPE_FOR_FIELD="Invalid type [%type%] for field [%field%]";
}
class Container extends BaseContainer
{
    var $__fields;
    function __construct($def,$neutralValue=null)
    {
        parent::__construct($def,null);
        $this->__fields=[];
        if(isset($this->definition["DEFAULT"]))
        {
            $this->setValue($this->definition["DEFAULT"]);
        }

    }
    function setValue($val)
    {
        if($val===null)
        {
            $this->valueSet=false;
            $this->__fields=[];
            return;
        }
        $curDef=$this->definition["FIELDS"];
        $nSet=0;
        foreach($curDef as $key=>$value)
        {
            $this->__fields[$key]=\model\reflection\Types\meta\TypeFactory::getType($this,$value);
            $wasEmpty=true;
            if(isset($val[$key])) {
                $this->__fields[$key]->setValue($val[$key]);
                if(!$this->__fields[$key]->isEmpty())
                    $wasEmpty=false;
            }
            if($wasEmpty==true)
            {
                if(isset($value["DEFAULT"]))
                    $this->__fields[$key]->setValue($value["DEFAULT"]);
                else
                {
                    if(isset($value["REQUIRED"]))
                        throw new ContainerException(ContainerException::ERR_REQUIRED_FIELD,["field"=>$key]);
                    if(isset($value["KEEP_KEY_ON_EMPTY"]))
                        $nSet++;
                }
            }
            else
                $nSet++;
        }
        if($nSet>0)
            $this->valueSet=true;
    }

    function validate($value)
    {
        if($value===null)
            return true;

        foreach($this->definition["FIELDS"] as $key=>$type)
        {
            $curDef=$this->definition["FIELDS"][$key];
            $tempType=\model\reflection\Types\meta\TypeFactory::getType($this,$curDef);
            if(!isset($value[$key]) && isset($curDef["REQUIRED"]) && $curDef["REQUIRED"]!=false)
                throw new ContainerException(ContainerException::ERR_REQUIRED_FIELD,array("field"=>$key));
            if(!$tempType->validate($value[$key]))
                return false;
            $tempType->setValue($value[$key]);
            if($curDef["REQUIRED"] && $tempType->hasValue()===false)
                throw new ContainerException(ContainerException::ERR_REQUIRED_FIELD,array("field"=>$key));
        }
        return true;
    }
    function getValue()
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
            return null;
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
    function equals($value)
    {
        if($this->valueSet===false && $value===null)
            return true;
        if(($this->valueSet==true && $value==null) || ($this->valueSet==false && value!==null))
            return false;
        foreach($this->__fields as $key=>$type) {
            if(!isset($value[$key]) && $this->__fields[$key]->hasOwnValue())
                return false;
            $curDef = $this->definition["FIELDS"][$key];
            $tempType=\model\reflection\Types\meta\TypeFactory::getType($this,$curDef);
            $tempType->setValue($value[$key]);
            if(!$tempType->equals($this->__fields[$key]->getValue()))
                return false;
        }
        return true;
    }
    // Warning : sin validacion.Acepta cualquier cosa.Usado por los formularios cuando un campo es erroneo.
    function __rawSet($value)
    {
        if($value===null)
            return $this->clear();
        foreach($this->definition["FIELDS"] as $key=>$def) {
            $this->__fields[$key]=\model\reflection\Types\meta\TypeFactory::getType($this,$def);
            if(isset($value[$key]))
                continue;
            $this->__fields[$key]->__rawSet($value[$key]);
        }
        $this->valueSet=true;
    }

    function set($value)
    {
        if(is_object($value) && get_class($value)==get_class($this))
            $value=$value->getValue();
        return $this->setValue($value);
    }

    function is_set()
    {
        return $this->valueSet;
    }

    function clear()
    {
        $this->valueSet=true;
        $this->value=null;
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
    function isEmpty()
    {
        return $this->valueSet==false;
    }
    function isTypeReference()
    {
        return false;
    }
    function __set($fieldName,$value)
    {
        if(!isset($this->definition["FIELDS"][$fieldName]))
            throw new BaseTypeException(\model\reflection\Types\meta\BaseTypeException::ERR_NOT_A_FIELD,array("field"=>$fieldName));
        $instance=\model\reflection\Types\meta\TypeFactory::getType($this,$this->definition["FIELDS"][$fieldName]);
        if(is_object($value))
        {
            if(get_class($value)!=get_class($instance))
                throw new ContainerException(ContainerException::ERR_INVALID_TYPE_FOR_FIELD,["type"=>get_class($value),"field"=>$fieldName]);

            $instance->validate($value->getValue());
            $this->__fields[$fieldName]=$value;
        }
        else
        {
            $instance->validate($value);
            $instance->setValue($value);
            $this->__fields[$fieldName]=$instance;
        }
        if($this->__fields[$fieldName]->hasOwnValue())
            $this->valueSet=true;
    }
    function __get($fieldName)
    {
        if($fieldName[0]=="*")
        {
            $fieldName=substr($fieldName,1);
            if(!isset($this->__fields[$fieldName]))
                throw new ContainerException(ContainerException::ERR_NOT_A_FIELD,["field"=>$fieldName]);
            return $this->__fields[$fieldName];
        }
        if(!isset($this->__fields[$fieldName]))
            throw new ContainerException(ContainerException::ERR_NOT_A_FIELD,["field"=>$fieldName]);
        return $this->__fields[$fieldName]->getValue();
    }
}
