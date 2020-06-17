<?php



namespace lib\tests\output\html;
use lib\data\Cursor\ArrayReaderCursor;
use lib\data\Cursor\Cursor;

$dirName= __DIR__ . "/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");


use PHPUnit\Framework\TestCase;
class RequestMocker
{
    var $actionData;
    function __construct($actionData)
    {
        $this->actionData=$actionData;
    }
    function getActionData()
    {
        return $this->actionData;
    }

}
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
            $conn->importDump(__DIR__."/../../model/stubs/samplemodel.sql");
            $this->testResolverIncluded=true;
        }
    }

    function testSimpleAddForm()
    {
        $this->init();

        $form=\lib\output\html\Form::getForm('\model\tests\User','AddAction',null);
        $hash=$form->createHash("testSite","testUrl",null,"");
        // AddAction\model\tests\UsertestSitetestUrl1234
        // AddAction\model\tests\UsertestSitetestUrl
        $request=new RequestMocker(
            [
                "name"=>"AddAction",
                "object"=>"/model/tests/User",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "INPUTS"=>[

                ],
                "FIELDS"=>[
                    "Name"=>"Pepito"
                ]
            ]
        );
        $form->resolve($request);

        $actionResult=$form->getResult();

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
        // EditAction\model\tests\UsertestSitetestUrlid1
        // EditAction\model\tests\UsertestSitetestUrl01
        $form=\lib\output\html\Form::getForm('\model\tests\User','EditAction',["id"=>1]);
        $hash=$form->createHash("testSite","testUrl",["id"=>1],"");
        $request=new RequestMocker(
            [
                "name"=>"EditAction",
                "object"=>"/model/tests/User",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "KEYS"=>[
                    "id"=>1
                ],
                "INPUTS"=>[

                ],
                "FIELDS"=>[
                    "Name"=>"Pepito",

                ]
            ]
        );

        $form->resolve($request);

        $actionResult=$form->getResult();

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

    function testPathEditAction()
    {
        $this->init();
        // EditAction\model\tests\UsertestSitetestUrlid1
        // EditAction\model\tests\UsertestSitetestUrl01
        $form=\lib\output\html\Form::getForm('\model\tests\Post','EditAction',["id"=>1]);
        $hash=$form->createHash("testSite","testUrl",["id"=>1],"");
        $request=new RequestMocker(
            [
                "name"=>"EditAction",
                "object"=>"/model/tests/Post",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "KEYS"=>[
                    "id"=>1
                ],
                "INPUTS"=>[

                ],
                "FIELDS"=>[
                    "Name"=>"Pepito",

                ]
            ]
        );

        $form->resolve($request);

        $actionResult=$form->getResult();

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

        $form=\lib\output\html\Form::getForm('\model\tests\User','DeleteAction',["id"=>1]);
        $hash=$form->createHash("testSite","testUrl",["id"=>1],"");
        $request=new RequestMocker(
            [
                "name"=>"DeleteAction",
                "object"=>"/model/tests/User",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "KEYS"=>[
                    "id"=>1
                ],
                "INPUTS"=>[

                ],
                "FIELDS"=>[
                ]
            ]
        );

        $form->resolve($request);
        // Se comprueba que la accion esta ok
        $actionResult=$form->getResult();

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
        $form=\lib\output\html\Form::getForm('\model\tests\User','AddAction',null);
        $hash=$form->createHash("testSite","testUrl",null,"");
        // AddAction\model\tests\UsertestSitetestUrl1234
        // AddAction\model\tests\UsertestSitetestUrl
        $request=new RequestMocker(
            [
                "name"=>"AddAction",
                "object"=>"/model/tests/User",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "INPUTS"=>[

                ],
                "FIELDS"=>[

                ]
            ]
        );
        $form->resolve($request);

        $actionResult=$form->getResult();

        $this->assertEquals(false,$actionResult->isOk());
        $fieldErrors=$actionResult->getFieldErrors();
        $keys=array_keys($fieldErrors["/Name"]);
        $this->assertEquals("lib\model\BaseTypedException::REQUIRED_FIELD",$keys[0]);
        $subKeys=array_keys($fieldErrors["/Name"][$keys[0]]);
        $this->assertEquals(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD,$subKeys[0]);
    }
/*

    function testErrorMissingKey()
    {
        $this->init();
        // EditAction\model\tests\UsertestSitetestUrlid1
        // EditAction\model\tests\UsertestSitetestUrl01
        $form=\lib\output\html\Form::getForm('\model\tests\Post','EditAction',["id"=>1]);
        $hash=$form->createHash("testSite","testUrl",["id"=>1],"");
        $request=new RequestMocker(
            [
                "name"=>"EditAction",
                "object"=>"/model/tests/Post",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "KEYS"=>[
                ],
                "INPUTS"=>[

                ],
                "FIELDS"=>[
                    "Name"=>"Pepito",

                ]
            ]
        );

        $this->expectException('lib\output\html\FormException');
        $this->expectExceptionCode(\lib\output\html\FormException::ERR_INVALID_FORM_HASH);
        $form->resolve($request);
    }*/

    function testComplexAction()
    {
        $this->init();
        $form=\lib\output\html\Form::getForm('\model\tests\Post','ComplexAction',null);
        $hash=$form->createHash("testSite","testUrl",null,"");
        // AddAction\model\tests\UsertestSitetestUrl1234
        // AddAction\model\tests\UsertestSitetestUrl
        $request=new RequestMocker(
            [
                "name"=>"ComplexAction",
                "object"=>"/model/tests/Post",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "INPUTS"=>[
                ],
                "FIELDS"=>[
                    "title"=>"Ultimo post",
                    "comments"=>[
                        ['title'=>'Comentario 1','content'=>'Content comentario 1'],
                        ['title'=>'Comentario 2','content'=>'Content comentario 2'],
                        ['title'=>'Comentario 3','content'=>'Content comentario 3']
                        ]
                ]
            ]
        );
        $form->resolve($request);

        $actionResult=$form->getResult();
        $this->assertEquals(true,$actionResult->isOk());

        // Se comprueba que se ha insertado el post y los comentarios:
        $ins=new \model\tests\Post();
        $ins->id=11;
        $ins->loadFromFields();
        $this->assertEquals("Ultimo post",$ins->title);
        $cnt=$ins->comments->count();
        $this->assertEquals(3,$cnt);
        $this->assertEquals("Content comentario 3",$ins->comments[2]->content);
    }

    // Igual que el anterior, pero se introduce un error en el tamanio maximo de un campo.
    function testComplexAction2()
    {
        $this->init();
        $form=\lib\output\html\Form::getForm('\model\tests\Post','ComplexAction',null);
        $hash=$form->createHash("testSite","testUrl",null,"");
        // AddAction\model\tests\UsertestSitetestUrl1234
        // AddAction\model\tests\UsertestSitetestUrl
        $request=new RequestMocker(
            [
                "name"=>"ComplexAction",
                "object"=>"/model/tests/Post",
                "site"=>"testSite",
                "validationCode"=>$hash,
                "page"=>"testUrl",
                "INPUTS"=>[
                ],
                "FIELDS"=>[
                    "title"=>"Ultimo post",
                    "comments"=>[
                        ['title'=>'Comentario 1','content'=>'Content comentario 1'],
                        ['title'=>'Comentario 2Comentario 2Comentario 2Comentario 2Comentario 2Comentario 2Comentario 2Comentario 2','content'=>'Content comentario 2'],
                        ['title'=>'Comentario 3','content'=>'Content comentario 3']
                    ]
                ]
            ]
        );
        $form->resolve($request);

        $actionResult=$form->getResult();
        // Se comprueba que la accion esta ok
        $this->assertEquals(false,$actionResult->isOk());

        $errors=$actionResult->getFieldErrors();
        $keys=array_keys($errors);
        $this->assertEquals("/comments/1/title",$keys[0]);
        $data=$errors[$keys[0]];
        $keys2=array_keys($data);
        $this->assertEquals('lib\model\types\_StringException::TOO_LONG',$keys2[0]);
    }

}
