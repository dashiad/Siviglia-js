<?php
/**
 * Class Package
 * @package model\reflection\tests
 *  (c) Smartclip
 */


namespace model\web\tests;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/vendor/autoload.php");


use PHPUnit\Framework\TestCase;

class PageTests extends TestCase
{

    function createNewPage()
    {
        $s=\Registry::getService("model");
        $instance=$s->getModel("/model/web/Page");
        $instance->name="Ejemplo";
        $instance->"";
        $instance->save();

    }
    function editPage($id)
    {
        $s=\Registry::getService("model");
        $instance=$s->getModel("/model/web/Page",["id_page"=>2]);
        $instance->name=""
        $instance->"";
        $instance->save();

    }
}

?>