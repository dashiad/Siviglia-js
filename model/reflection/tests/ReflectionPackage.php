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
        \Registry::getService("model")->addPackage($testPackage);
        $package=new \model\reflection\ReflectionPackage("test");
        return $package;
    }
    function testListObjects()
    {
        $pkg=$this->getPackageInstance();
        $models=$pkg->getModels();
        $this->assertEquals(4,count($models));
        for($k=0;$k<count($models);$k++)
        {
            $r[$models[$k]["name"]]=$models[$k];
        }
        $keys=array_keys($r);
        $this->assertEquals(true,in_array("ClassA",$keys));
        $this->assertEquals(true,in_array("ClassB",$keys));
        $this->assertEquals(true,in_array("Post",$keys));
        $this->assertEquals(true,in_array("User",$keys));
        $this->assertEquals(1,count($r["Post"]["subobjects"]));
        $this->assertEquals(3,count($r["User"]["subobjects"]));
    }
}
