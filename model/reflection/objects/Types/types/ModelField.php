<?php namespace model\reflection\Types\types;

class ModelField extends \lib\model\types\Container
{
    function getMeta()
    {
        return $this->getDerivedTypeMeta("ModelField");
    }
}
