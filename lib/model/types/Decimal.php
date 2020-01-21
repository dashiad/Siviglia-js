<?php namespace lib\model\types;
class Decimal extends BaseType
{
    function _setValue($val)
    {
        $this->value=$val;
        $this->valueSet=true;
    }
    function _getValue()
    {
        return $this->value;
    }
    function _copy($val)
    {
        $this->value=$val->value;
    }
    function _validate($val)
    {
        return is_numeric($val);
    }
    function _equals($v)
    {
        return $this->value==$v->value;
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Decimal.php");
        return '\model\reflection\Types\meta\Decimal';
    }

}
