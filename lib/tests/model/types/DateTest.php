<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Date.php");


use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{

    function testDefinition1()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date",
            "DEFAULT"=>"NOW"
        ]);
        $v=$ins->getValue();
        $cDate=date(\lib\model\types\Date::DATE_FORMAT,time());
        $this->assertEquals($cDate,$ins->getValue());
    }
    function testDefinition2()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date"
        ]);
        $ins->setValue("2019-01-01");
        $v=$ins->getValue();
        $this->assertEquals("2019-01-01",$ins->getValue());
    }
    function testDefinition3()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date",
            "STARTYEAR"=>"2019"
        ]);

        $this->expectException('\lib\model\types\DateException');
        $this->expectExceptionCode(\lib\model\types\DateException::ERR_START_YEAR);
        $ins->setValue("2018-01-01");
    }
    function testDefinition4()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date",
            "ENDYEAR"=>"2019"
        ]);

        $this->expectException('\lib\model\types\DateException');
        $this->expectExceptionCode(\lib\model\types\DateException::ERR_END_YEAR);
        $ins->setValue("2021-01-01");
    }
    function testDefinition5()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date",
            "STRICTLYPAST"=>true
        ]);
        $this->expectException('\lib\model\types\DateException');
        $this->expectExceptionCode(\lib\model\types\DateException::ERR_STRICTLY_PAST);
        $ins->setValue("2027-01-01");
    }
    function testDefinition6()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date",
            "STRICTLYFUTURE"=>true
        ]);
        $this->expectException('\lib\model\types\DateException');
        $this->expectExceptionCode(\lib\model\types\DateException::ERR_STRICTLY_FUTURE);
        $ins->setValue("2000-01-01");
    }
    function testValue1()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date"
        ]);
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);
        $ins->setValue("aaa");
    }
    function testValue2()
    {
        $ins=new \lib\model\types\Date([
            "TYPE"=>"Date"
        ]);
        $ins->setValue("16-06-2020");
        $p=$ins->getValue();
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);

    }
}
