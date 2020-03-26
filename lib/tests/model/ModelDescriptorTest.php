<?php
/**
 * Class ModelNameTest
 * @package model\reflection\Model\ModelName\tests
 *  (c) Smartclip
 */


namespace lib\tests\model;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;
class ModelDescriptorTest extends TestCase
{

    function testSimple()
    {
        $package=new \lib\model\Package('\model\reflection','');
        $obj=new \lib\model\ModelDescriptor('\model\reflection\Model',null,$package);
        $layer=$obj->getLayer();
        $class=$obj->getClassName();
        $namespace=$obj->getParentNamespace();
        $namespaced=$obj->getNamespaced();
        $private=$obj->isPrivate();
        $baseDir=$obj->getPath("Model.php");
        $this->assertEquals("reflection",$layer);
        $this->assertEquals("Model",$class);
        $this->assertEquals('\model\reflection',$namespace);
        $this->assertEquals('\model\reflection\Model',$namespaced);
        $this->assertEquals(false,$private);
        $this->assertEquals(realpath(PROJECTPATH."/model/reflection/objects/Model/Model.php"),realpath($baseDir));

    }
    function testPrivate()
    {
        $obj=\lib\model\ModelService::getModelDescriptor('\model\reflection\Model\ModelName');
        $layer=$obj->getLayer();
        $class=$obj->getClassName();
        $namespace=$obj->getParentNamespace();
        $namespaced=$obj->getNamespaced();
        $private=$obj->isPrivate();
        $baseDir=$obj->getPath("ModelName.php");
        $this->assertEquals("reflection",$layer);
        $this->assertEquals("ModelName",$class);
        $this->assertEquals('\model\reflection\Model',$namespace);
        $this->assertEquals('\model\reflection\Model\ModelName',$namespaced);
        $this->assertEquals(true,$private);
        $this->assertEquals(realpath(PROJECTPATH."/model/reflection/objects/Model/objects/ModelName/ModelName.php"),realpath($baseDir));
    }
    function testDoublePrivate()
    {
        $obj=\lib\model\ModelService::getModelDescriptor('\model\reflection\Storage\ES\ESOptionsDefinition');
        $layer=$obj->getLayer();
        $class=$obj->getClassName();
        $namespace=$obj->getParentNamespace();
        $namespaced=$obj->getNamespaced();
        $private=$obj->isPrivate();
        $baseDir=$obj->getPath("ESOptionsDefinition.php");
        $this->assertEquals("reflection",$layer);
        $this->assertEquals("ESOptionsDefinition",$class);
        $this->assertEquals('\model\reflection\Storage\ES',$namespace);
        $this->assertEquals('\model\reflection\Storage\ES\ESOptionsDefinition',$namespaced);
        $this->assertEquals(true,$private);
        $this->assertEquals(realpath(PROJECTPATH."/model/reflection/objects/Storage/objects/ES/objects/ESOptionsDefinition/ESOptionsDefinition.php"),realpath($baseDir));
    }
    function testSimpleDefaultPaths()
    {
        $obj=\lib\model\ModelService::getModelDescriptor('\model\web\Page');
        $dsPath=$obj->getDataSourceFileName("View");
        $this->assertEquals(realpath($dsPath),realpath(PROJECTPATH."/model/web/objects/Page/datasources/View.php"));

        $actionPath=$obj->getActionFileName("AddAction");
        $this->assertEquals(realpath($actionPath),realpath(PROJECTPATH."/model/web/objects/Page/actions/AddAction.php"));

        $formPath=$obj->getFormFileName("AddAction");
        $this->assertEquals(realpath($formPath),realpath(PROJECTPATH."/model/web/objects/Page/html/forms/AddAction.php"));

        $namespacedForm=$obj->getNamespacedForm("AddAction");
        $this->assertEquals('\model\web\Page\html\forms\Add',$namespacedForm);
    }

    function testPrivateDefaultPaths()
    {
        $obj=\lib\model\ModelService::getModelDescriptor('\model\web\Site\WebsiteUrls');
        $dsPath=$obj->getDataSourceFileName("View");
        $this->assertEquals(realpath($dsPath),realpath(PROJECTPATH."/model/web/objects/Site/objects/WebsiteUrls/datasources/View.php"));

        $actionPath=$obj->getActionFileName("AddAction");
        $this->assertEquals(realpath($actionPath),realpath(PROJECTPATH."/model/web/objects/Site/objects/WebsiteUrls/actions/AddAction.php"));

        $formPath=$obj->getFormFileName("AddAction");
        $this->assertEquals(realpath($formPath),realpath(PROJECTPATH."/model/web/objects/Site/objects/WebsiteUrls/html/forms/AddAction.php"));

        $namespacedForm=$obj->getNamespacedForm("AddAction");
        $this->assertEquals('\model\web\Site\WebsiteUrls\html\forms\AddAction',$namespacedForm);
    }
}
