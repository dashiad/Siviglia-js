<?php
namespace model\reflection\tests;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

include_once(__DIR__."/stubs/model/tests/Package.php");
class Model extends TestCase
{
    var $initialized=false;
    function initialize()
    {
        if(!$this->initialized)
        {
            $this->initialized=true;
            \Registry::getService("model")->addPackage(new \model\tests\Package());
        }
    }
    function testLoad()
    {
        $this->initialize();
        $md=new \model\reflection\Model('\model\tests\User');
        $md1=new \model\reflection\Model('\model\tests\User\UserRole');
        $md->initialize();
        $md1->initialize();
    }
}