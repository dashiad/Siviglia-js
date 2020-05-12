<?php
/**
 * Class PackageTest
 * @package lib\tests\model
 *  (c) Smartclip
*/

namespace lib\tests\model;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use lib\model\Package;
use PHPUnit\Framework\TestCase;
class PackageTest extends TestCase
{

    var $testResolverIncluded=false;
    function getTestPackage()
    {
        return new \lib\tests\model\stubs\model\tests\Package();
    }

    function init()
    {
        if ($this->testResolverIncluded == false) {
            \Registry::getService("model")->addPackage($this->getTestPackage());
        }
    }
    function testGetInfoFromClass()
    {
        $classExamples=[
            '\model\web\Site\Definition'=>["package"=>"web",
                                            "model"=>"Site",
                                            "submodel"=>null,
                                            "resource"=>\lib\model\Package::DEFINITION,
                                            "class"=>'\model\web\Site\Definition',
                                            'file'=>PROJECTPATH."/model/web/objects/Site/Definition.php"],
            '\model\web\Site\datasources\FullList'=>[
                "package"=>"web",
                "model"=>"Site",
                "submodel"=>null,
                "item"=>"FullList",
                "resource"=>\lib\model\Package::DATASOURCE,
                "class"=>'\model\web\Site\datasources\FullList',
                'file'=>PROJECTPATH."/model/web/objects/Site/datasources/FullList.php"
            ],
            '\model\web\Site\WebsiteCountries'=>[
                "package"=>"web",
                "model"=>"Site",
                "submodel"=>"WebsiteCountries",
                "resource"=>\lib\model\Package::MODEL,
                "class"=>"\model\web\Site\WebsiteCountries",
                'file'=>PROJECTPATH."/model/web/objects/Site/objects/WebsiteCountries/WebsiteCountries.php"
            ],
            '\model\web\Site\WebsiteCountries\Definition'=>[
                "package"=>"web",
                "model"=>"Site",
                "submodel"=>"WebsiteCountries",
                "resource"=>\lib\model\Package::DEFINITION,
                "class"=>"\model\web\Site\WebsiteCountries\Definition",
                'file'=>PROJECTPATH."/model/web/objects/Site/objects/WebsiteCountries/Definition.php"
            ],
            '\model\web\Site'=>[
                "package"=>"web",
                "model"=>"Site",
                "submodel"=>"",
                "resource"=>\lib\model\Package::MODEL,
                "class"=>"\model\web\Site",
                'file'=>PROJECTPATH."/model/web/objects/Site/Site.php"
            ],
            '\model\tests\ClassA'=>[
                "package"=>"tests",
                "model"=>"ClassA",
                "submodel"=>"",
                "resource"=>\lib\model\Package::MODEL,
                "class"=>'\model\tests\ClassA',
                'file'=>PROJECTPATH."lib/tests/model/stubs/model/tests/objects/ClassA/ClassA.php"
            ],
        ];
        $this->init();
        foreach($classExamples as $k=>$v)
        {
            $info=\lib\model\Package::getInfoFromClass($k);
            foreach($info as $k1=>$v1)
            {
                if($k1=="file")
                    $this->assertEquals(realpath($v[$k1]),realpath($v1));
                else
                    $this->assertEquals($v[$k1],$v1);
            }
            $info=\lib\model\Package::getInfo($info["package"],$info["model"],isset($info["submodel"])?$info["submodel"]:null,$info["resource"],$info["item"]);
            foreach($info as $k1=>$v1)
            {
                if($k1=="file")
                    $this->assertEquals(realpath($v[$k1]),realpath($v1));
                else
                    $this->assertEquals($v[$k1],$v1);
            }
        }
    }

    function testGetAllItems()
    {
        $info=\lib\model\Package::getInfo("web","Site",null,\lib\model\Package::DATASOURCE,"*");
        $this->assertEquals(6,count($info));
        $info=\lib\model\Package::getInfo("reflection","Model",null,\lib\model\Package::TYPE,"*");

    }


}
