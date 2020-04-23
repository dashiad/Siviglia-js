<?php


namespace model\Reflection;
include_once(__DIR__."/Definition.php");

class MetaDefinition extends \lib\model\BaseTypedObject
{
    function __construct()
    {
        $def=\model\reflection\MetaDefinition\Definition::$definition;
        parent::__construct($def);
    }
}
