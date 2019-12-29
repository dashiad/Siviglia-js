<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 16/06/14
 * Time: 23:15
 */
namespace model\reflection\Types\meta;
class PHPVariableException extends \model\reflection\Meta\BaseMetadataException {

}

class PHPVariable extends \model\reflection\Types\meta\BaseType {

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
