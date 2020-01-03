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
        $ins=new \lib\model\types\ModelField();
        $ins->setValue(["MODEL"=>'\model\tests\User',"FIELD"=>'Name']);
        $h=22;
    }
    function testDefinition2()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer",
            "MAX"=>3
        ]);
        $this->expectException('\lib\model\types\IntegerException');
        $this->expectExceptionCode(\lib\model\types\IntegerException::ERR_TOO_BIG);
        $ins->setValue(4);
        $ins->setValue(2);
        $this->assertEquals(2,$ins->getValue());
    }
    function testDefinition3()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer"
        ]);
        $this->expectException('\lib\model\types\IntegerException');
        $this->expectExceptionCode(\lib\model\types\IntegerException::ERR_NOT_A_NUMBER);
        $ins->setValue("aaq");

    }

    function testDefinition6()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer",
            "FIXED"=>8
        ]);
        $v=$ins->getValue();
        $this->assertEquals(8,$v);
        $this->assertEquals(true,$ins->hasValue());
        $ins->setValue(4);
        $this->assertEquals(8,$v);

    }

    function testDefinition8()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer",
            "DEFAULT"=>"122"
        ]);
        $this->assertEquals(122,$ins->getValue());
        $this->assertEquals(true,$ins->hasValue());
    }

    function testDefinition11()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer"
        ]);
        $ins2=new \lib\model\types\Integer([
            "TYPE"=>"Integer"
        ]);
        $ins->setValue(4);
        $ins2->copy($ins);
        $this->assertEquals(4,$ins2->getValue());
    }
    function testDefinition12()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer",
            "MIN"=>5

        ]);
        $ins->__rawSet(3);
        $this->assertEquals(3,$ins->getValue());
    }
    function testDefinition13()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer"
        ]);
        $ins->setValue(8);
        $this->assertEquals(true,$ins->equals(8));
    }
    function testSource()
    {
        $ins=new \lib\model\types\Integer([
            "TYPE"=>"Integer",
            "SOURCE"=>[
                "TYPE"=>"Array",
                "VALUES"=>[1,2,11]
            ]
        ]);
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);
        $ins->setValue(14);
        $ins->setValue(11);
        $this->assertEquals(11,$ins->getValue());
    }

}
