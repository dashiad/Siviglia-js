<?php
/**
 * Class SimpleModelService
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\data\res;


class SimplePackage extends \lib\model\PlainPackage
{
    function __construct()
    {
        parent::__construct("/model/Ads",__DIR__);
    }
    function getModelDescriptor($modelName)
    {
        return new \lib\tests\data\res\SimpleModelDescriptor($modelName,$this);
    }

}
