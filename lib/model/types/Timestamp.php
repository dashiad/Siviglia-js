<?php
namespace lib\model\types;
class Timestamp extends BaseType
{
        function __construct($definition,$value=false)
        {
                $definition["TYPE"]="Timestamp";
                $definition["DEFAULT"]="NOW";
                BaseType::__construct($definition,$value);
                //$this->flags |= BaseType::TYPE_NOT_EDITABLE;
                $this->flags |= BaseType::TYPE_SET_ON_ACCESS;
        }
        function _validate($v)
        {
            return true;
        }
        function _setValue($v)
        {
            if($v==="NOW")
                $this->value=time();
            else
                $this->value=intval($v);
            $this->valueSet=true;
        }
        function _getValue()
        {
            if(!$this->valueSet)
                return time();
            return $this->value;
        }
        function getMetaClassName()
        {
            include_once(PROJECTPATH."/model/reflection/objects/Types/Timestamp.php");
            return '\model\reflection\Types\meta\Timestamp';
        }
        function _equals($value)
        {
            return $this->value==$value;
        }
        function _copy($type)
        {
            $this->setValue($type->getValue());
        }
}
