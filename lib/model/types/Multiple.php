<?php
namespace lib\model\types;
class Multiple extends BaseType
{
    public $deffield;

    function __construct($def,$neutralValue=null)
    {
        $this->definition=$def;
    }

    function setDeffield($value)
    {
        $this->deffield = $value;
    }
}