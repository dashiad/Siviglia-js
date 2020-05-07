<?php
namespace lib\model\types;
class State extends Enum
{


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
