<?php
namespace lib\model\types;
class DictionaryException extends \lib\model\types\BaseTypeException
{
    const ERR_INVALID_VALUE=101;
    const ERR_INVALID_KEY=102;
    const TXT_INVALID_VALUE="This dictionary doesnt accept values of type [%type%]";
    const TXT_INVALID_KEY="Invalid key:[%key%]";
}
class Dictionary extends \lib\model\types\BaseContainer
{
    function __construct($def,$neutralValue=null)
    {
        parent::__construct($def,null);
        $this->value=null;
    }
    function _setValue($val)
    {
        $this->valueSet = false;
        if ($val === null) {

            $this->value = null;
            return;
        }
        $nSet=0;
        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $subType->setValue($value);
            $subType->setParent($this,$key);
            $this->value[$key] = $subType;
            $nSet++;
        }
        if($nSet>0)
            $this->valueSet=true;
    }

    function _validate($val)
    {
        if($val===null)
            return true;

        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $subType->setParent($this,$key);
            $subType->validate($value);
        }
        return true;
    }
    function _copy($ins)
    {
        $val=$ins->value;
        foreach($val as $key=>$value)
        {
            $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $subType->setValue($value->getValue());
            $subType->setParent($this,$key);
        }
    }
    function _getValue()
    {
        if($this->valueSet==false)
            return null;

        $nSet=0;
        $result=[];

        foreach($this->value as $key=>$value)
        {
            $result[$key]=$value->getValue();
            $nSet++;
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
    function _equals($value)
    {
        if($this->value==null && $value==null)
            return true;
        if(($value==null && $this->value!==null) || ($this->value!==null && $value==null))
            return false;
        $k1=array_keys($value);
        $k2=array_keys($this->value);
        $diff=array_diff($k1,$k2);
        if(count($diff)>0)
            return false;
        $diff=array_diff($k2,$k1);
        if(count($diff)>0)
            return false;
        foreach($this->value as $k=>$v)
        {
            if(!$this->value[$k]->equals($value[$k]))
                return false;
        }
        return true;
    }
    function add($key,$value)
    {
        if(!is_a($value,'\model\reflection\Types\meta\BaseType'))
        {
            $v=\lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $v->setParent($this,$key);
            $v->setValue($value);
            $this->value[$key]=$v;
        }
        else
            $this->value[$key]=$value;
        $this->valueSet=true;
    }
    function remove($key)
    {
        if($this->value===null)
            return;
        unset($this->value[$key]);
        $n=count(array_keys($this->value));
        if($n==0)
        {
            $this->value=null;
            $this->valueSet=false;
        }
    }
    function getKeys()
    {
        if($this->value==null)
            return [];
        return array_keys($this->value);
    }

    // Warning : sin validacion.Acepta cualquier cosa.Usado por los formularios cuando un campo es erroneo.
    function __rawSet($val)
    {
        $this->valueSet = false;
        if ($val === null) {
            $this->clear();
            return;
        }
        $nSet=0;
        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);

            $subType->__rawSet($value);
            $subType->setParent($this,$key);
            $this->value[$key] = $subType;
            $nSet++;
        }
        if($nSet>0)
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
        $this->valueSet=false;
        $this->value=null;
    }

    function __toString()
    {
        return json_encode($this->getValue());
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
        $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
        if(is_object($value))
        {
            if(get_class($subType)==get_class($value)) {
                $this->value[$fieldName] = $value;
                $value->setParent($this,$fieldName);
                if($value->hasOwnValue())
                    $this->valueSet=true;
            }
            else
                throw new DictionaryException(DictionaryException::ERR_INVALID_VALUE,["type"=>get_class($value)],$this);
        }
        else
        {
            $subType->setValue($value);
            $subType->setParent($this);
            if($subType->hasOwnValue()) {
                $this->value[$fieldName]=$subType;
                $this->valueSet = true;
            }

        }
    }
    function __get($fieldName)
    {
        if($fieldName==="[[KEYS]]")
            return array_keys($this->value);
        if($fieldName[0]=="*")
        {
            $fieldName=substr($fieldName,1);
            if(!isset($this->value[$fieldName]))
                throw new DictionaryException(DictionaryException::ERR_INVALID_KEY,["key"=>$fieldName],$this);
            return $this->value[$fieldName];
        }
        if(!isset($this->value[$fieldName]))
            throw new DictionaryException(DictionaryException::ERR_INVALID_KEY,["key"=>$fieldName],$this);
        return $this->value[$fieldName]->getValue();
    }
    function getEmptyValue()
    {
        return [];
    }
    // Un diccionario, indepenedientemente de la key, siempre tiene el mismo tipo.
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
        // Consumimos un field, que deberia ser la key.
        $field=array_shift($path);
        $type = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
        $type->setParent($this,$path[0]);
        return $type->getTypeFromPath($path);
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Dictionary.php");
        return '\model\reflection\Types\meta\Dictionary';
    }
}
