<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");


use PHPUnit\Framework\TestCase;

class _StringTest extends TestCase
{

    function testDefinition1()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "MINLENGTH"=>3
        ]);
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $ins->setValue("aa");
        $ins->setValue("aaaa");
        $this->assertEquals("aaaa",$ins->getValue());
    }
    function testDefinition2()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "MAXLENGTH"=>4
        ]);
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_LONG);
        $ins->setValue("aaaaaa");
        $ins->setValue("aa");
        $this->assertEquals("aa",$ins->getValue());
    }
    function testDefinition3()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "REGEXP"=>"%aaa%"
        ]);
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_INVALID_CHARACTERS);
        $ins->setValue("aaq");
        $ins->setValue("aaaa");
        $this->assertEquals("aaaa",$ins->getValue());
    }
    function testDefinition4()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "SET_ON_EMPTY"=>false
        ]);
        $ins->setValue("");
        $v=$ins->hasValue();
        $this->assertEquals(false,$ins->hasValue());
    }
    function testDefinition5()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "SET_ON_EMPTY"=>true
        ]);
        $v=$ins->getValue();
        $this->assertEquals("",$v);
        $this->assertEquals(true,$ins->hasValue());
    }
    function testDefinition6()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "FIXED"=>"Hola"
        ]);
        $v=$ins->getValue();
        $this->assertEquals("Hola",$v);
        $this->assertEquals(true,$ins->hasValue());
        $ins->setValue("Adios");
        $this->assertEquals("Hola",$v);

    }
    function testDefinition7()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "EXCLUDE"=>["Hola"]
        ]);
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_EXCLUDED_VALUE);
        $ins->setValue("Hola");
        $ins->setValue("aaaa");
        $this->assertEquals("aaaa",$ins->getValue());
    }
    function testDefinition8()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "DEFAULT"=>"NOne"
        ]);
        $this->assertEquals("NOne",$ins->getValue());
        $this->assertEquals(true,$ins->hasValue());
    }
    function testDefinition9()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "TRIM"=>true
        ]);
        $ins->setValue("   aaaa    ");
        $this->assertEquals("aaaa",$ins->getValue());
    }
    function testDefinition10()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "NORMALIZE"=>true
        ]);
        $ins->setValue("Aó.É#");
        $this->assertEquals("ao e",$ins->getValue());
    }
    function testDefinition11()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String"
        ]);
        $ins2=new \lib\model\types\_String([
            "TYPE"=>"String"
        ]);
        $ins->setValue("hola");
        $ins2->copy($ins);
        $this->assertEquals("hola",$ins2->getValue());
    }
    function testDefinition12()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "MINLENGTH"=>5

        ]);
        $ins->__rawSet("hola");
        $this->assertEquals("hola",$ins->getValue());
    }
    function testDefinition13()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String"
        ]);
        $ins->setValue("hola");
        $this->assertEquals(true,$ins->equals("hola"));
    }
    function testSource()
    {
        $ins=new \lib\model\types\_String([
            "TYPE"=>"String",
            "SOURCE"=>[
                "TYPE"=>"Array",
                "VALUES"=>["a","b","c"]
            ]
        ]);
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);
        $ins->setValue("d");
        $ins->setValue("b");
        $this->assertEquals("b",$ins->getValue());
    }

}
