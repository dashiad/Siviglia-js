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
        return new \lib\model\types\_Array("",[
            "TYPE"=>"Array",
            "ELEMENTS"=>[
                  "TYPE"=>"String"
                ]
        ]);
    }


    function testSimple()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one","two"]);
        $this->assertEquals("one",$cnt[0]);
        $this->assertEquals("two",$cnt[1]);
        $this->assertEquals(2,$cnt->count());
    }



}
