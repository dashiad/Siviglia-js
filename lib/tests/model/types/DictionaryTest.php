<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Dictionary.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");

use PHPUnit\Framework\TestCase;

class DictionaryTest extends TestCase
{
    function getContainerDefinition()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                "two"=>["TYPE"=>"String","DEFAULT"=>"Hola"]
            ]
        ];
    }

    function getDefinition1()
    {
        return new \lib\model\types\Dictionary("",[
            "TYPE"=>"Dictionary",
            "VALUETYPE"=>$this->getContainerDefinition()
        ]);
    }
    function getContainerInstance()
    {
        return new \lib\model\types\Container("",$this->getContainerDefinition());
    }

    function testSimple()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["first"=>["one"=>"tres","two"=>"lalas"],"second"=>["one"=>"ee","two"=>"ss"]]);
        $v=$cnt->first;
        $this->assertEquals("tres",$v["one"]);
        $this->assertEquals("ss",$cnt->second["two"]);
    }

    function testNull()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(null);
        $this->assertEquals(false,$cnt->hasOwnValue());
    }
    function testGet()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["first"=>["one"=>"tres","two"=>"lalas"],"second"=>["one"=>"ee","two"=>"ss"]]);
        $val=$cnt->getValue();
        $this->assertEquals("tres",$val["first"]["one"]);
        $this->assertEquals("ee",$val["second"]["one"]);

    }
    function testSet()
    {
        $cnt=$this->getDefinition1();
        $cnt->probando=["one"=>"uno","two"=>"dos"];
        $val=$cnt->getValue();
        $this->assertEquals("uno",$val["probando"]["one"]);
        $c1=$this->getContainerInstance();
        $c1=["one"=>"lala","two"=>"lolo"];
        $cnt->probando2=$c1;
        $val=$cnt->getValue();
        $this->assertEquals("lolo",$val["probando2"]["two"]);
    }
    function testSetNull()
    {
        $cnt=$this->getDefinition1();
        $cnt->probando=["one"=>null,"two"=>null];
        $is_set=$cnt->hasOwnValue();
        $this->assertEquals(true,$is_set);
    }
    function testRemove()
    {
        $cnt=$this->getDefinition1();
        $cnt->probando=["one"=>"lala","two"=>"cucu"];
        $is_set=$cnt->hasOwnValue();
        $this->assertEquals(true,$is_set);
        $cnt->remove("probando");
        $is_set=$cnt->hasOwnValue();
        $this->assertEquals(false,$is_set);
    }
}