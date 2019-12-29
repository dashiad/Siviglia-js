<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");
include_once(PROJECTPATH."/lib/model/types/_Array.php");

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    function getDefinition1()
    {
        return new \lib\model\types\_Array([
            "TYPE"=>"Array",
            "ELEMENTS"=>[
                  "TYPE"=>"String"
                ]
        ]);
    }
    function getDefinition2()
    {
        return new \lib\model\types\_Array([
            "TYPE"=>"Array",
            "ELEMENTS"=>[
                "TYPE"=>"Container",
                "FIELDS"=>[
                    "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                    "two"=>["TYPE"=>"String","REQUIRED"=>true],
                    ]
                ]
        ]);
    }
    function getDefinition5()
    {
        return new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10,"KEEP_KEY_ON_EMPTY"=>true],
                "two"=>["TYPE"=>"String"]
            ],
            "DEFAULT"=>[
                "one"=>"1111",
                "two"=>"2222"
            ]
        ]);
    }
    function testSimple()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>"tres","two"=>"lalas"]);
        $this->assertEquals("tres",$cnt->one);
        $this->assertEquals("lalas",$cnt->two);
    }

    function testDefault()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>"tres"]);
        $this->assertEquals("Hola",$cnt->two);
    }

    function testMissing()
    {
        $cnt=$this->getDefinition2();
        $this->expectException('\lib\model\types\ContainerException');
        $this->expectExceptionCode(\lib\model\types\ContainerException::ERR_REQUIRED_FIELD);
        $cnt->setValue(["one"=>"tres"]);
    }
    function testInvalid()
    {
        $cnt=$this->getDefinition1();
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $cnt->setValue(["one"=>"a","two"=>"lalas"]);
    }
    function testValidateInvalid()
    {
        $cnt=$this->getDefinition1();
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $cnt->validate(["one"=>"a","two"=>"lalas"]);
    }
    function testValidateMissing()
    {
        $cnt=$this->getDefinition2();
        $this->expectException('\lib\model\types\ContainerException');
        $this->expectExceptionCode(\lib\model\types\ContainerException::ERR_REQUIRED_FIELD);
        $cnt->validate(["one"=>"tres"]);
    }
    function testNull()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(null);
        $this->assertEquals(false,$cnt->hasOwnValue());
    }
    function testGetValue()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>"tres","two"=>"lalas"]);
        $tmp=$cnt->getValue();
        $this->assertEquals("tres",$tmp["one"]);
    }
    function testGetEmptyValue()
    {
        $cnt=$this->getDefinition1();
        $tmp=$cnt->getValue();
        $this->assertEquals(null,$tmp);
    }
    function testNullableKeys()
    {
        $cnt=$this->getDefinition2();
        $cnt->setValue(["one"=>"tres","two"=>"lalas"]);
        $tmp=$cnt->getValue();
        $this->assertEquals(false,isset($tmp["three"]));
        $this->assertEquals(null,$tmp["four"]);
    }
    function testDefaultOnNull()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>null,"two"=>null]);
        $this->assertEquals("Hola",$cnt->two);
    }
    function testNullOnNullValues()
    {
        $cnt=$this->getDefinition3();
        $cnt->setValue(["one"=>null,"two"=>null]);
        $this->assertEquals(false,$cnt->hasOwnValue());
        $this->assertEquals(null,$cnt->getValue());
    }
    function testNotNullOnPreserveNullKey()
    {
        $cnt=$this->getDefinition4();
        $cnt->setValue(["one"=>null,"two"=>null]);
        $this->assertEquals(true,$cnt->hasOwnValue());
        $this->assertEquals(null,$cnt->one);
    }
    function testDefaultValue()
    {
        $cnt=$this->getDefinition5();
        $this->assertEquals("1111",$cnt->one);
    }

}