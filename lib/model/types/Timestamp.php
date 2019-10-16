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
        }
}
