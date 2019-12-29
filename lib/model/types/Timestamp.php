<?php
namespace lib\model\types;
class Timestamp extends DateTime
{
        function __construct($definition,$value=false)
        {
                $definition["TYPE"]="Timestamp";
                $definition["DEFAULT"]="NOW";
                DateTime::__construct($definition,$value);
                $this->flags |= BaseType::TYPE_NOT_EDITABLE;
                $this->flags |= BaseType::TYPE_SET_ON_ACCESS;
        }
        function _getValue()
        {
            if(!$this->valueSet)
                return time();
            return $this->value;
        }
        function getMetaClassName()
        {
            include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Timestamp.php");
            return '\model\reflection\Types\meta\Timestamp';
        }
}
