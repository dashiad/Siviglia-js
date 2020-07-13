<?php
/**
 * Class SimpleModelService
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\model\stubs\model\tests;


class Package extends \lib\model\Package
{
    function __construct()
    {
        parent::__construct('\model\tests','/lib/tests/model/stubs');
    }
    function getModelDescriptor($modelName)
    {
        return new \lib\tests\model\stubs\model\tests\ModelDescriptor($modelName,$this);
    }
}
