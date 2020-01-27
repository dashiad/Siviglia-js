<?php



namespace lib\tests\output\html;
use lib\data\Cursor\ArrayReaderCursor;
use lib\data\Cursor\Cursor;

$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");


use PHPUnit\Framework\TestCase;
class FormTest extends TestCase
{
    var $testResolverIncluded;
    function getTestPackage()
    {
        return new \lib\tests\model\stubs\model\tests\Package();
    }

    function init()
    {
        if ($this->testResolverIncluded == false) {
            \Registry::getService("model")->addPackage($this->getTestPackage());
            $serService=\Registry::getService("storage");
            global $Config;
            $sc=$Config["SERIALIZERS"]["default"]["ADDRESS"];
            $serService->addSerializer("web",

                [
                    "TYPE"=>"Mysql",
                    "NAME"=>"web",
                    "ADDRESS"=>[
                        "host" => $sc["host"],
                        "user" => $sc["user"],
                        "password" => $sc["password"],
                        "database"=>"modeltests"
                    ]
                ]

            );
            $serializer=$serService->getSerializerByName("web");

            $conn=$serializer->getConnection();
            $conn->importDump(__DIR__."/../model/stubs/samplemodel.sql");
            $this->testResolverIncluded=true;
        }
    }
    function testSimpleAddAction()
    {
        $this->init();
        $act=\lib\output\html\Form::getForm('\model\tests\User','AddAction');

        $actionResult=new \lib\action\ActionResult();
        global $oCurrentUser;
        $instance=$act->getParametersInstance();
        $instance->Name="Pepito";
        $act->process($instance, $actionResult, $oCurrentUser);
        // Se comprueba que la accion esta ok
        $this->assertEquals(true,$actionResult->isOk());
        // Se comprueba que se ha insertado el usuario.
        $ds=\lib\datasource\DataSourceFactory::getDataSource('\model\tests\User','FullList');
        $ds->Name='Pepito';
        $it=$ds->fetchAll();
        $this->assertEquals(1,$it->count());
        $id=$it[0]->id;
        $this->assertEquals(5,$id);

    }
    function testSimpleEditAction()
    {
        $this->init();
        $act=\lib\action\Action::getAction('\model\tests\User','EditAction');

        $actionResult=new \lib\action\ActionResult();
        global $oCurrentUser;
        $instance=$act->getParametersInstance();
        $instance->id=1;
        $instance->Name="Pepito";
        $act->process($instance, $actionResult, $oCurrentUser);
        // Se comprueba que la accion esta ok
        $this->assertEquals(true,$actionResult->isOk());
        // Se comprueba que se ha insertado el usuario.
        $ds=\lib\datasource\DataSourceFactory::getDataSource('\model\tests\User','FullList');
        $ds->Name='Pepito';
        $it=$ds->fetchAll();
        $this->assertEquals(1,$it->count());
        $id=$it[0]->id;
        $this->assertEquals(1,$id);
    }
    function testSimpleDeleteAction()
    {
        $this->init();
        $act=\lib\action\Action::getAction('\model\tests\User','DeleteAction');

        $actionResult=new \lib\action\ActionResult();
        global $oCurrentUser;
        $instance=$act->getParametersInstance();
        $instance->id=1;
        $act->process($instance, $actionResult, $oCurrentUser);
        // Se comprueba que la accion esta ok
        $this->assertEquals(true,$actionResult->isOk());
        // Se comprueba que se ha insertado el usuario.
        $ds=\lib\datasource\DataSourceFactory::getDataSource('\model\tests\User','FullList');
        $ds->id=1;
        $it=$ds->fetchAll();
        $this->assertEquals(0,$it->count());
    }
    function testErrorRequiredField()
    {
        $this->init();
        $act=\lib\action\Action::getAction('\model\tests\User','AddAction');

        $actionResult=new \lib\action\ActionResult();
        global $oCurrentUser;
        $instance=$act->getParametersInstance();
        $act->process($instance, $actionResult, $oCurrentUser);
        // Se comprueba que la accion esta ok
        $this->assertEquals(false,$actionResult->isOk());
        $fieldErrors=$actionResult->getFieldErrors();
        $keys=array_keys($fieldErrors["Name"]);
        $this->assertEquals("lib\model\BaseTypedException::REQUIRED_FIELD",$keys[0]);
        $subKeys=array_keys($fieldErrors["Name"][$keys[0]]);
        $this->assertEquals(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD,$subKeys[0]);
    }
    function testErrorMissingKey()
    {
        $this->init();
        $act=\lib\action\Action::getAction('\model\tests\User','EditAction');

        $actionResult=new \lib\action\ActionResult();
        global $oCurrentUser;
        $instance=$act->getParametersInstance();

        $instance->Name="Pepito";
        $act->process($instance, $actionResult, $oCurrentUser);
        // Se comprueba que la accion esta ok
        $this->assertEquals(false,$actionResult->isOk());
        $globalErrors=$actionResult->getGlobalErrors();
        $keys=array_keys($globalErrors);
        $this->assertEquals("lib\action\ActionException::CANT_EDIT_WITHOUT_KEY",$keys[0]);

    }

}
