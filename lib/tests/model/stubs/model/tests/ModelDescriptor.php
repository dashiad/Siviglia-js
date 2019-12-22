<?php
/**
 * Class SimpleModelDescriptor
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\model\stubs\model\tests;


class ModelDescriptor extends \lib\model\ModelDescriptor
{
    function __construct($name,$package)
    {
        parent::__construct($name,null,$package);
    }
}
