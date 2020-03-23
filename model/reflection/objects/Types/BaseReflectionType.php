<?php
/**
 * Class BaseReflectionType
 * @package model\reflection\Types\types
 *  (c) Smartclip
 */


namespace model\reflection\Types;


class BaseReflectionType extends \lib\model\types\Container
{
    function __construct()
    {
        return parent::__construct($this->getMeta());
    }
}
