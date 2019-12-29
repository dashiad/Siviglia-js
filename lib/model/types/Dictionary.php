<?php
namespace lib\model\types;
class DictionaryException extends \lib\model\types\BaseTypeException
{
    const ERR_INVALID_VALUE=101;
    const ERR_INVALID_KEY=102;
    const TXT_INVALID_VALUE="This dictionary doesnt accept values of type [%type%]";
    const TXT_INVALID_KEY="Invalid key:[%key%]";
}
class Dictionary extends BaseContainer
{
    function __construct($def,$neutralValue=null)
    {
        parent::__construct($def,null);
        $this->value=null;
    }
    function _setValue($val)
    {
        $this->valueSet = false;
        $nSet=0;
        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $subType->setParent($this);
            $subType->__rawSet($value);
            $this->value[$key] = $subType;

            $nSet++;
        }
        if($nSet>0)
            $this->valueSet=true;
    }

    function _validate($val)
    {
        foreach ($val as $key => $value) {
            $subType = \lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $subType->setParent($this);
            if(!$subType->validate($value))
                return false;
        }
        return true;
    }
    function _getValue()
    {
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
        if(!is_a($value,'\lib\model\types\BaseType'))
        {
            $v=\lib\model\types\TypeFactory::getType($this, $this->definition["VALUETYPE"]);
            $v->setParent($this);
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
        $subType->setParent($this);
        if(is_object($value))
        {
            if(get_class($subType)==get_class($value)) {
                $this->value[$fieldName] = $value;
                if($value->hasOwnValue())
                    $this->valueSet=true;
            }
            else
                throw new DictionaryException(DictionaryException::ERR_INVALID_VALUE,["type"=>get_class($value)]);
        }
        else
        {

            $subType->setValue($value);
            if($subType->hasOwnValue()) {
                $this->value[$fieldName]=$subType;
                $this->valueSet = true;
            }

        }
    }
    function __get($fieldName)
    {
        if($fieldName[0]=="*")
        {
            $fieldName=substr($fieldName,1);
            if(!isset($this->value[$fieldName]))
                throw new DictionaryException(DictionaryException::ERR_INVALID_KEY,["key"=>$fieldName]);
            return $this->value[$fieldName];
        }
        if(!isset($this->value[$fieldName]))
            throw new DictionaryException(DictionaryException::ERR_INVALID_KEY,["key"=>$fieldName]);
        return $this->value[$fieldName]->getValue();
    }

    function _copy($val)
    {
        $f=$val->getValue();
        $this->__rawSet($f);
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Dictionary.php");
        return '\model\reflection\Types\meta\Dictionary';
    }

    function __getPathProperty($pathProperty,$mode)
    {
        if($pathProperty[0]=="{")
        {
            $pathProperty=substr($pathProperty,1,-1);
            if($pathProperty=="keys")
            {
                if(!$this->valueSet)
                    return [];
                return array_keys($this->value);
            }
            $results=[];
            if($this->value!==null) {
                foreach ($this->value as $k => $v)
                    $results[] = $v->getPath($pathProperty);
            }
            return $results;
        }

        if(isset($this->value[$pathProperty]))
        {
            if($mode=="reference")
                return $this->value[$pathProperty];
            else
                return $this->value[$pathProperty]->getValue();
        }


    }
}