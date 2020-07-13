<?php
/**
 * Class SimpleModelService
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\permissions\stubs\model\tests;


class Package extends \lib\model\Package
{
    function __construct()
    {
        parent::__construct('\model\tests','/lib/tests/permissions/stubs');
    }
    function getModelDescriptor($modelName)
    {
        return new \lib\tests\permissions\stubs\model\tests\ModelDescriptor($modelName,$this);
    }
}
