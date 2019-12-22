<?php
/**
 * Class ModelServiceTest
 * @package lib\tests\model
 *  (c) Smartclip
 */

namespace lib\tests\model;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

class ModelServiceTest extends TestCase
{
    var $testResolverIncluded=false;
    function getTestPackage()
    {
        include_once(__DIR__ . "/stubs/model/tests/Package.php");
        return new \lib\tests\model\stubs\model\tests\Package();
    }
    function getModelService()
    {
        if($this->testResolverIncluded==false)
        {
            \Registry::getService("model")->addPackage("model",$this->getTestPackage());
            $this->testResolverIncluded=true;
        }

    }
    function testCreate()
    {
        $this->getModelService();
        \lib\model\ModelService::includeModel("/model/tests/ClassA");
        $this->assertEquals(true,class_exists('\model\tests\ClassA',false));
    }
    function testTestPaths()
    {
        $this->getModelService();
        \lib\model\ModelService::includeModel("/model/tests/ClassA");
        $descriptor=\lib\model\ModelService::getModelDescriptor("/model/tests/ClassA");
        $path=$descriptor->getFormFileName("Test");

        $path=str_replace("//","/",$path);

        $this->assertEquals(realpath(__DIR__.DIRECTORY_SEPARATOR."stubs/model/tests/objects/ClassA/html/forms/Test.php"),realpath($path));
    }

    // Nos aseguramos de que funciona el autoloader.
    function testGetNamespaced()
    {
        $this->getModelService();
        try{
            $ins=new \model\tests\ClassA();
            $this->assertEquals(true,true);
        }catch(\Exception $e)
        {
            $this->fail("Excepcion al crear modelo de prueba.");
        }
    }

}
