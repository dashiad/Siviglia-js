<?php
namespace lib\model\types;
class TypeReferenceValueException extends BaseTypeException {
    const ERR_NOT_DEFINED=1;
    const ERR_NO_MODEL=2;
}
class TypeReferenceValue extends Text implements ReferencesModel
{
    var $parentType;
    function setValue($value)
    {
        $this->getParentType()->setValue($value);
    }
    function getValue()
    {
        return $this->getParentType()->getValue();
    }

    function setModel($model)
    {
        $this->model=$model;
    }
    function getParentType()
    {
        if(!$this->parentType)
        {
            if(!$this->model)
                throw new TypeReferenceValueException(TypeReferenceValueException::ERR_NO_MODEL);
            $this->parentType=$model->{"*".$this->definition["DEFINED_BY"]}->getTypeInstance();
        }
        return $this->parentType;
    }
    function setParentType($type)
    {
        $this->parentType=$type;
    }
}

