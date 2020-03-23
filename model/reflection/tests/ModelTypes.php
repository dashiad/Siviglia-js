<?php
/**
 * Class ModelTypes
 * @package model\reflection\tests
 *  (c) Smartclip
 */


namespace model\reflection\tests;

include_once(__DIR__ . "/../../../install/config/CONFIG_test.php");
include_once(LIBPATH . "/startup.php");
include_once(LIBPATH . "/autoloader.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

include_once(__DIR__ . "/stubs/model/tests/Package.php");


class ModelTypes extends TestCase
{
    function testBaseTypedObjectType()
    {
        $info=\lib\model\Package::getInfo("reflection","Model",null,\lib\model\Package::TYPE,"BaseTypedObject");
        include_once($info["file"]);
        $class=$info["class"];
        $instance=new $class();
        // Se va a cargar un modelo, y se va a asignar el valor de la definicion, a la instancia de BaseTypedObject
        $info2=\lib\model\Package::getInfo("web","Site",null,\lib\model\Package::DEFINITION,null);
        include_once($info2["file"]);
        $defClass=$info2["class"];
        $def=$defClass::$definition;
        $myVal=["FIELDS"=>$def["FIELDS"]];
        $instance->setValue($myVal);
        $f1=$instance->{"*FIELDS"}->{"*id_site"}->TYPE;

        $this->assertEquals("AutoIncrement",$f1);
        $this->assertEquals("id_website",$instance->{"*FIELDS"}->{"*id_site"}->LABEL);
        $val=$instance->getValue();
        $h=11;
    }
}
