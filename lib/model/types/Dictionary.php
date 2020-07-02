<?php
namespace lib\model\types;
class DictionaryException extends \lib\model\types\BaseTypeException
{
    const ERR_INVALID_VALUE=101;
    const ERR_INVALID_KEY=102;
    const TXT_INVALID_VALUE="This dictionary doesnt accept values of type [%type%]";
    const TXT_INVALID_KEY="Invalid key:[%key%]";
}
class Dictionary extends \lib\model\types\BaseType implements \ArrayAccess
{

    function _setValue($val,$validationMode=null)
    {
        if($validationMode===null)
            $validationMode=$this->validationMode;
        $this->valueSet = false;
        if ($val === null) {

            $this->value = null;
            return;
        }
        $nSet=0;
        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$this->fieldNamePath], $this->__definition["VALUETYPE"],$this,null,$this->validationMode);
            // Es necesario asignarnos antes el tipo, y luego darle el valor, ya que
            // al darle valor, se va a buscar el campo dentro del diccionario. Si no se ha asignado ya, no se encontrara
            $this->valueSet=true;
            $this->value[$key] = $subType;
            $subType->apply($value,$validationMode);
            $nSet++;
        }
        if($nSet>0)
            $this->valueSet=true;
    }

    function _validate($val)
    {
        if($val===null)
            return true;
        if($this->__onlyValidating==true) {
            foreach ($val as $key => $value) {
                $subType = \lib\model\types\TypeFactory::getType(["fieldName" => $key, "path" => $this->fieldNamePath], $this->__definition["VALUETYPE"], $this, null, $this->validationMode);
                $subType->validate($value);
            }
        }
        return true;
    }
    function __checkSource($value)
    {
        if(!$this->__hasSource())
            return true;

        // El diccionario chequea sus keys contra el source
        foreach($value as $k=>$v) {
            $s=$this->__getSource(true);
            if(!$s->contains($k))
                return false;
        }
        return true;
    }
    function _copy($ins)
    {
        $val=$ins->value;
        $this->value=[];
        foreach($val as $key=>$value)
        {
            $subType = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$this->fieldNamePath],$this->__definition["VALUETYPE"],$this,$value->getValue,$this->validationMode);
            $this->value[$key]=$subType;
        }
    }
    function __setValidationMode($mode)
    {
        $this->validationMode=$mode;
        if($this->value!==null) {
            foreach ($this->value as $k => $v)
                $v->__setValidationMode($mode);
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
    function __getReference()
    {
        return $this;
    }

    function __hasValue()
    {
        return $this->valueSet;
    }
    function __hasOwnValue()
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
            $this->value[$key]=\lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$this->fieldNamePath], $this->__definition["VALUETYPE"],$this,$value,$this->validationMode);
        }
        else {
            $value->__setParent($this,$key);
            $value->__setValidationMode($this->validationMode);
            $this->value[$key] = $value;
        }
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
            $this->__clear();
            return;
        }
        $nSet=0;
        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType(["fieldName"=>$key,"path"=>$this->fieldNamePath], $this->__definition["VALUETYPE"],$this,null,$this->validationMode);
            $subType->__rawSet($value);
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
        return $this->apply($value);
    }

    function is_set()
    {
        return $this->valueSet;
    }

    function __clear()
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
        $subType = \lib\model\types\TypeFactory::getType(["fieldName"=>$fieldName,"path"=>$this->fieldNamePath], $this->__definition["VALUETYPE"],$this,null,$this->validationMode);
        if(is_object($value))
        {
            if(get_class($subType)==get_class($value)) {
                $this->value[$fieldName] = $value;
                if($value->__hasOwnValue())
                    $this->valueSet=true;
            }
            else
                throw new DictionaryException(DictionaryException::ERR_INVALID_VALUE,["type"=>get_class($value)],$this);
        }
        else
        {
            $subType->apply($value);

            if($subType->__hasOwnValue()) {
                $this->value[$fieldName]=$subType;
                $this->valueSet = true;
            }
        }
    }
    function __getField($fieldName)
    {
        return $this->__get($fieldName);
    }
    function __get($fieldName)
    {
        if($fieldName==="[[KEYS]]") {
            if(!$this->valueSet)
                return [];
            return array_keys($this->value);
        }
        if(!$this->valueSet)
            return null;
        if($fieldName[0]=="*")
        {
            $fieldName=substr($fieldName,1);
            if(!isset($this->value[$fieldName]))
                throw new DictionaryException(DictionaryException::ERR_INVALID_KEY,["key"=>$fieldName],$this);
            return $this->value[$fieldName];
        }
        if(!isset($this->value[$fieldName]))
            throw new DictionaryException(DictionaryException::ERR_INVALID_KEY,["key"=>$fieldName],$this);
        return $this->value[$fieldName]->__getReference();
    }
    function __getEmptyValue()
    {
        return [];
    }
    // Un diccionario, indepenedientemente de la key, siempre tiene el mismo tipo.
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
        // Consumimos un field, que deberia ser la key.
        $field=array_shift($path);
        $type = \lib\model\types\TypeFactory::getType(["fieldName"=>$path[0],"path"=>$this->fieldNamePath], $this->__definition["VALUETYPE"],$this,null,$this->validationMode);
        return $type->__getTypeFromPath($path);
    }

    public function offsetExists ( $offset ){
        return $this->valueSet && isset($this->value[$offset]);
    }
    public function offsetGet ( $offset )
    {

        return $this->__get($offset);
    }
    public function offsetSet ( $offset , $value )
    {
        if(!$this->subNode)
            return null;
        return $this->__set($offset,$value);
    }
    public function offsetUnset ( $offset ) {
        $this->remove($offset);
    }
}
