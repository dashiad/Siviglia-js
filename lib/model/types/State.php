<?php
namespace lib\model\types;
class State extends Enum
{
    function __construct(& $definition,$value=null)
    {
        Enum::__construct($definition,$value);
    }

    function getDefaultState()
    {
        return $this->definition["DEFAULT"];
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/State.php");
        return '\model\reflection\Types\meta\State';
    }
}
