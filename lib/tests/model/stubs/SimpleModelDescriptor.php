<?php
/**
 * Class SimpleModelDescriptor
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\model\stubs;


class SimpleModelDescriptor extends \lib\model\ModelDescriptor
{
    function __construct($name,$package)
    {
        parent::__construct($name,null,$package);
    }
}
