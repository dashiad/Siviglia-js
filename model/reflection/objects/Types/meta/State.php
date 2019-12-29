<?php
namespace model\reflection\Types\meta;
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
}
