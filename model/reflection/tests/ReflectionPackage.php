<?php
/**
 * Class Package
 * @package model\reflection\tests
 *  (c) Smartclip
 */


namespace model\reflection\tests;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

class ReflectionPackage extends TestCase
{
    function getPackageInstance()
    {
        $testPackage=new \lib\tests\model\stubs\model\tests\Package();
        \Registry::getService("model")->addPackage("test",$testPackage);
        $package=new \model\reflection\ReflectionPackage("test");
        return $package;
    }
    function testListObjects()
    {
        $pkg=$this->getPackageInstance();
        $pkg->getModels();
    }
}
