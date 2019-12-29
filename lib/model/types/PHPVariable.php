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

    function _setValue($val)
    {
        $this->value=$val;
    }
    function _getValue()
    {
        return $this->value;
    }
    function _copy($val)
    {
        $this->value=$val->value;
    }
    function _equals($v)
    {
        return $this->value==$v->value;
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/PHPVariable.php");
        return '\model\reflection\Types\meta\PHPVariable';
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
