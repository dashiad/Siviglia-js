<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 16/06/14
 * Time: 23:15
 */
namespace lib\model\types;
class PHPVariableException extends BaseTypeException {

}

class PHPVariable extends \lib\model\types\BaseType {

    function setValue($val)
    {
        parent::setValue($val);
    }

    function getUnserializedValue()
    {
        if($this->valueSet)
            return unserialize($this->value);
        if($this->hasDefaultValue())
            return unserialize($this->getDefaultValue());
        return null;
    }
}
