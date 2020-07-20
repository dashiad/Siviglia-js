<?php
/**
 * Class SimpleModelService
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace model\tests;

include_once(__DIR__."/ModelDescriptor.php");
class Package extends \lib\model\Package
{
    function __construct()
    {
        parent::__construct('\model\tests','/model/reflection/tests/stubs');
    }
    function getModelDescriptor($modelName)
    {
        return new \model\tests\ModelDescriptor($modelName,$this);
    }
}
