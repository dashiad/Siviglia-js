<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Enum.php");


use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{

    function testDefinition1()
    {
        $ins=new \lib\model\types\Enum([
            "TYPE"=>"Enum",
            "VALUES"=>["Uno","Dos","Tres","Cuatro"],
            "DEFAULT"=>"Uno"
        ]);
        $v=$ins->getValue();
        $this->assertEquals(0,$ins->getValue());
        $ins->setValue(1);
        $this->assertEquals("Dos",$ins->getLabelFromValue($ins->getValue()));
        $this->assertEquals("Dos",$ins->getLabel());
        $this->assertEquals(3,$ins->getValueFromLabel("Cuatro"));
    }

}
