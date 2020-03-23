<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
class ModelField extends \model\reflection\Types\BaseReflectionType
{
    function getMeta()
    {
        return $this->getDerivedTypeMeta("ModelField");
    }
}
