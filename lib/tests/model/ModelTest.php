<?php

namespace lib\tests\model;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    var $testResolverIncluded = false;


    function getTestPackage()
    {
        return new \lib\tests\model\stubs\model\tests\Package();
    }

    function init()
    {
        if ($this->testResolverIncluded == false) {
            \Registry::getService("model")->addPackage("test",$this->getTestPackage());
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
            $conn->importDump(__DIR__."/stubs/samplemodel.sql");
            $this->testResolverIncluded=true;
        }
    }

    function testLoadSimple()
    {
        $this->init();
        $ins=new \model\tests\User();
        $ins->id=1;
        $ins->loadFromFields();
        $name=$ins->Name;
        $this->assertEquals("User1",$name);
    }
    function testSimpleRelation()
    {
        $this->init();
        $ins=new \model\tests\Post();
        $ins->id=1;
        $ins->loadFromFields();
        $name=$ins->creator_id->Name;
        $this->assertEquals("User1",$name);
    }
    function testSimpleSave()
    {
        $this->init();
        $ins=new \model\tests\Post();
        $ins->id=1;
        $ins->loadFromFields();
        $ins->title="Nuevo";
        $ins->save();

        $ins=new \model\tests\Post();
        $ins->id=1;
        $ins->loadFromFields();
        $this->assertEquals("Nuevo",$ins->title);
    }
    function testRelationSave()
    {
        $this->init();
        $ins=new \model\tests\Post();
        $ins->id=1;
        $ins->loadFromFields();
        $ins->creator_id->Name="NuevoName";
        $ins->save();

        $ins=new \model\tests\User();
        $ins->id=1;
        $ins->loadFromFields();
        $this->assertEquals("NuevoName",$ins->Name);
    }
    function testNewObjectSave()
    {
        $this->init();
        $user=new \model\tests\User();
        $user->id=1;
        $user->loadFromFields();
        $ins=new \model\tests\Post();
        $ins->creator_id=$user->id;
        $ins->title="TITULIN";
        $ins->save();
        $id=$ins->id;
        $this->assertEquals(11,$id);
    }
    function testDelete()
    {
        $this->init();
        $user=new \model\tests\User();
        $user->id=1;
        $user->loadFromFields();
        $user->delete();

        $user=new \model\tests\User();
        $user->id=1;
        $this->expectException('\lib\model\BaseModelException');
        $this->expectExceptionCode(\lib\model\BaseModelException::ERR_UNKNOWN_OBJECT);
        $user->loadFromFields();


    }

    function testSimpleRelationSave()
    {
        $this->init();
        $ins=new \model\tests\Post();
        $ins->creator_id->Name="Lalas";
        $ins->title="TITULIN";
        $ins->save();
        $id=$ins->{"*creator_id"}->getValue();
        // La relacion tiene que tener el valor 5
        $this->assertEquals(5,$id);
        // Y debe existir un nuevo usuario, con valor 5
        $user=new \model\tests\User();
        $user->id=$id;
        $user->loadFromFields();
        $this->assertEquals("Lalas",$user->Name);

    }

    function testAlias()
    {
        $this->init();
        $ins=new \model\tests\User;
        $ins->id=1;
        $ins->loadFromFields();
        $nPosts=$ins->posts->count();
        $this->assertEquals(3,$nPosts);
        $title=$ins->posts[0]->title;
        $this->assertEquals("Post-1",$title);
        $nComments=$ins->posts[0]->comments->count();
        $this->assertEquals(6,$nComments);
        $cId=$ins->posts[0]->comments[0]->id;
        $ins->posts[0]->comments[0]->title="Nuevo";
        $ins->save();

        // Cargamos el comentario para asegurar que se ha cambiado el titulo.
        $comment=new \model\tests\Post\Comment;
        $comment->id=$cId;
        $comment->loadFromFields();
        $this->assertEquals("Nuevo",$comment->title);
    }

    function testMultipleInverse()
    {
        $this->init();
        $ins=new \model\tests\User;
        $ins->id=1;
        $ins->loadFromFields();
        $n=$ins->roles->count();
        $this->assertEquals(2,$n);

        // Se intenta aniadir un nuevo rol, usando una instancia del objeto remoto.
        $role=new \model\tests\User\Roles();
        $role->id_role=3;
        $role->loadFromFields();
        $ins->roles->add($role);

        // Se ve si se ha aniadido
        $n=$ins->roles->count();
        $this->assertEquals(3,$n);

        $roleName=$ins->roles[2]->id_role->role;
        $this->assertEquals("role3",$roleName);

        // Borrado por instancia de la clase relacion.
        $ins->roles->delete($ins->roles[0]);
        $n=$ins->roles->count();
        $this->assertEquals(2,$n);

        // Borrado por id de la clase relacion
        $id=$ins->roles[0]->id;
        $ins->roles->delete($id);
        $n=$ins->roles->count();
        $this->assertEquals(1,$n);
        // Borrado por instancia de la clase remota
        $rem=$ins->roles[0]->id_role[0];
        $ins->roles->delete($rem);
        $n=$ins->roles->count();
        $this->assertEquals(0,$n);

        // Se intenta aniadir un nuevo rol, usando un id del objeto remoto.
        $ins->roles->add(1);
        $n=$ins->roles->count();
        $this->assertEquals(1,$n);

    }
    function testExtends()
    {
        $this->init();
        $ins=new \model\tests\User\Admin();
        $ins->id_user=1;
        $ins->loadFromFields();
        // Se accede a un metodo de la clase "Base"
        $name=$ins->Name;
        $this->assertEquals("User1",$name);
        // Se llama a un metodo de la clase "Base"
        $r=$ins->callMe();
        $this->assertEquals(3,$r);
        // Se guarda un campo de la clase "Base"
        $ins->Name="UserModified";
        // Y se modifica un campo de la clase "Derivada"
        $ins->adminrole="Master";
        $ins->save();

        // Se carga la clase "Base", para comprobar el save()
        $ins=new \model\tests\User();
        $ins->id=1;
        $ins->loadFromFields();
        $name=$ins->Name;
        $this->assertEquals("UserModified",$ins->Name);
    }

}
