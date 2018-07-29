<?php
namespace lib\model\types;
class TypeReferenceException extends BaseTypeException {
    const ERR_NOT_DEFINED=1;
    const ERR_NO_MODEL=2;
}

class TypeReference extends PHPVariable
{
    var $model=null;
    var $typeInstance=null;
    function getTypeInstance()
    {
        if(!$this->hasValue())
            throw new TypeReferenceException(TypeReferenceException::ERR_NOT_DEFINED);
        if($this->typeInstance==null)
        {
            $this->typeInstance=TypeFactory::getType(null,$this->value);
        }
        return $this->typeInstance;
    }

    function setValue($value)
    {
        if(is_a($value,'\lib\model\types\BaseType'))
        {
            // Es "mi" valor
            $this->value=$value->definition;
            if($value->hasOwnValue())
            {
                $f=$this->getTypeInstance();
                $f->setValue($value->getValue());
            }
        }
        else
        {
            $f=$this->getTypeInstance();
            $f->setValue($value->getValue());
        }
    }
    function setRawValue($value)
    {
        $this->valueSet=true;
        $this->value=$value;
    }
}

class TypeReferenceHTMLSerializer extends BaseTypeHTMLSerializer
{
}

class TypeReferenceMYSQLSerializer extends PHPVariableMYSQLSerializer
{
    var $typeSer;
    var $typeInstance;

    function serialize($type)
    {
        if($type->hasValue())
        {
            return $type->getRawValue();
        }
        return "NULL";
    }

    function unserialize($type,$values)
    {
        $type->setRawValue($values[$label]);
    }
}
