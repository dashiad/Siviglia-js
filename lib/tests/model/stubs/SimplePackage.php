<?php
/**
 * Class SimpleModelService
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\model\stubs;


class SimplePackage extends \lib\model\Package
{
    function __construct()
    {
        parent::__construct("/model/tests",__DIR__);
    }
    function getModelDescriptor($modelName)
    {
        return new \lib\tests\model\stubs\SimpleModelDescriptor($modelName,$this);
    }
}
