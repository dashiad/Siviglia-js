<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/ModelField.php");


use PHPUnit\Framework\TestCase;

class ModelFieldTest extends TestCase
{

    var $testResolverIncluded = false;


    function getTestPackage()
    {
        return new \lib\tests\model\stubs\model\tests\Package();
    }

    function init()
    {
        if ($this->testResolverIncluded == false) {
            \Registry::getService("model")->addPackage($this->getTestPackage());
            $this->testResolverIncluded=true;
        }

    }

    function testDefinition1()
    {
        $this->init();
        $ins=new \lib\model\types\ModelField("",null);
        $ins->setValue(["MODEL"=>'\model\tests\User',"FIELD"=>'Name']);
        $this->assertEquals(true,$ins->hasOwnValue());
        $field=$ins->FIELD;
        $this->assertEquals("Name",$field);
    }
    function testDefinition2()
    {
        $this->init();
        $ins=new \lib\model\types\ModelField("",null);
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);
        $ins->setValue(["MODEL"=>'\model\tests\User',"FIELD"=>'Named']);
    }
    function testDefinition3()
    {
        $this->init();
        $ins=new \lib\model\types\ModelField("",null);
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);
        $ins->setValue(["MODEL"=>'\model\tests\User2',"FIELD"=>'Name']);
    }
    function testDefinition4()
    {
        $this->init();
        $ins=new \lib\model\types\ModelField("",null);
        $ins->setValue(["MODEL"=>'\model\tests\User',"FIELD"=>'Name']);

        $ins2=new \lib\model\types\ModelField("",null);
        $ins2->copy($ins);
        $field=$ins2->FIELD;
        $this->assertEquals(true,$ins2->hasOwnValue());
        $this->assertEquals("Name",$field);
    }



}
