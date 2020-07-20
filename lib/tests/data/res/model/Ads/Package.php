<?php
/**
 * Class SimpleModelService
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\data\res\model\Ads;


class Package extends \lib\model\Package
{
    function __construct()
    {
        parent::__construct("\model\Ads",'/lib/tests/data/res');
    }
    function getModelDescriptor($modelName)
    {
        return new \lib\tests\data\res\model\Ads\ModelDescriptor($modelName,$this);
    }
  /*  public function includeFile($className)
    {
        $fileName=$this->basePath."/".str_replace('\\','/',$className).".php";
        include_once($fileName);
    }*/
}
