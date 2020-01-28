<?php

namespace lib\tests\model;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use BASE_SITE\Box as Box;

use PHPUnit\Framework\TestCase;
use \model\web\Job;
use \model\web\Worker;

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
            \Registry::getService("model")->addPackage($this->getTestPackage());
            $serService = \Registry::getService("storage");
            global $Config;
            $sc = $Config["SERIALIZERS"]["default"]["ADDRESS"];
            $serService->addSerializer("web",

                [
                    "TYPE" => "Mysql",
                    "NAME" => "web",
                    "ADDRESS" => [
                        "host" => $sc["host"],
                        "user" => $sc["user"],
                        "password" => $sc["password"],
                        "database" => "modeltests"
                    ]
                ]

            );
            $serializer = $serService->getSerializerByName("web");

            $conn = $serializer->getConnection();
            $conn->importDump(__DIR__ . "/stubs/samplemodel.sql");
            $this->testResolverIncluded = true;
        }
    }
    function testInstance()
    {
        $this->init();
        $ins=new \model\tests\ClassA();
        $ins->simpleDate='2020-01-01 13:00';
        $h=11;
    }
    function testLoadSimple()
    {
        $this->init();
        $ins = new \model\tests\User();
        $ins->id = 1;
        $ins->loadFromFields();
        $name = $ins->Name;
        $this->assertEquals("User1", $name);
    }

    function testSimpleRelation()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $name = $ins->creator_id->Name;
        $this->assertEquals("User1", $name);
    }

    function testSimpleSave()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $ins->title = "Nuevo";
        $ins->save();

        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $this->assertEquals("Nuevo", $ins->title);
    }

    function testRelationSave()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $ins->creator_id->Name = "NuevoName";
        $ins->save();

        $ins = new \model\tests\User();
        $ins->id = 1;
        $ins->loadFromFields();
        $this->assertEquals("NuevoName", $ins->Name);
    }

    function testNewObjectSave()
    {
        $this->init();
        $user = new \model\tests\User();
        $user->id = 1;
        $user->loadFromFields();
        $ins = new \model\tests\Post();
        $ins->creator_id = $user->id;
        $ins->title = "TITULIN";
        $ins->save();
        $id = $ins->id;
        $this->assertEquals(11, $id);
    }

    function testDelete()
    {
        $this->init();
        $user = new \model\tests\User();
        $user->id = 1;
        $user->loadFromFields();
        $user->delete();

        $user = new \model\tests\User();
        $user->id = 1;
        $this->expectException('\lib\model\BaseModelException');
        $this->expectExceptionCode(\lib\model\BaseModelException::ERR_UNKNOWN_OBJECT);
        $user->loadFromFields();


    }

    function testSimpleRelationSave()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->creator_id->Name = "Lalas";
        $ins->title = "TITULIN";
        $ins->save();
        $id = $ins->{"*creator_id"}->getValue();
        // La relacion tiene que tener el valor 5
        $this->assertEquals(5, $id);
        // Y debe existir un nuevo usuario, con valor 5
        $user = new \model\tests\User();
        $user->id = $id;
        $user->loadFromFields();
        $this->assertEquals("Lalas", $user->Name);

    }

    // Accediendo a traves de un path a un campo.
    function testPathRelationSave()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->{"creator_id/Name"} = "Lalas";
        $ins->title = "TITULIN";
        $ins->save();
        $id = $ins->{"*creator_id"}->getValue();
        // La relacion tiene que tener el valor 5
        $this->assertEquals(5, $id);
        // Y debe existir un nuevo usuario, con valor 5
        $user = new \model\tests\User();
        $user->id = $id;
        $user->loadFromFields();
        $this->assertEquals("Lalas", $user->Name);

    }


    function testAlias()
    {
        $this->init();
        $ins = new \model\tests\User;
        $ins->id = 1;
        $ins->loadFromFields();
        $nPosts = $ins->posts->count();
        $this->assertEquals(3, $nPosts);
        $title = $ins->posts[0]->title;
        $this->assertEquals("Post-1", $title);
        $nComments = $ins->posts[0]->comments->count();
        $this->assertEquals(6, $nComments);
        $cId = $ins->posts[0]->comments[0]->id;
        $ins->posts[0]->comments[0]->title = "Nuevo";
        $ins->save();

        // Cargamos el comentario para asegurar que se ha cambiado el titulo.
        $comment = new \model\tests\Post\Comment;
        $comment->id = $cId;
        $comment->loadFromFields();
        $this->assertEquals("Nuevo", $comment->title);
    }


    function testMultipleInverse()
    {
        $this->init();
        $ins = new \model\tests\User;
        $ins->id = 1;
        $ins->loadFromFields();
        $n = $ins->roles->count();
        $this->assertEquals(2, $n);

        // Se intenta aniadir un nuevo rol, usando una instancia del objeto remoto.
        $role = new \model\tests\User\Roles();
        $role->id_role = 3;
        $role->loadFromFields();
        $ins->roles->add($role);

        // Se ve si se ha aniadido
        $n = $ins->roles->count();
        $this->assertEquals(3, $n);

        $roleName = $ins->roles[2]->id_role->role;
        $this->assertEquals("role3", $roleName);

        // Borrado por instancia de la clase relacion.
        $ins->roles->delete($ins->roles[0]);
        $n = $ins->roles->count();
        $this->assertEquals(2, $n);

        // Borrado por id de la clase relacion
        $id = $ins->roles[0]->id;
        $ins->roles->delete($id);
        $n = $ins->roles->count();
        $this->assertEquals(1, $n);
        // Borrado por instancia de la clase remota
        $rem = $ins->roles[0]->id_role[0];
        $ins->roles->delete($rem);
        $n = $ins->roles->count();
        $this->assertEquals(0, $n);

        // Se intenta aniadir un nuevo rol, usando un id del objeto remoto.
        $ins->roles->add(1);
        $n = $ins->roles->count();
        $this->assertEquals(1, $n);

    }

    function testExtends()
    {
        $this->init();
        $ins = new \model\tests\User\Admin();
        $ins->id_user = 1;
        $ins->loadFromFields();
        // Se accede a un metodo de la clase "Base"
        $name = $ins->Name;
        $this->assertEquals("User1", $name);
        // Se llama a un metodo de la clase "Base"
        $r = $ins->callMe();
        $this->assertEquals(3, $r);
        // Se guarda un campo de la clase "Base"
        $ins->Name = "UserModified";
        // Y se modifica un campo de la clase "Derivada"
        $ins->adminrole = "Master";
        $ins->save();

        // Se carga la clase "Base", para comprobar el save()
        $ins = new \model\tests\User();
        $ins->id = 1;
        $ins->loadFromFields();
        $name = $ins->Name;
        $this->assertEquals("UserModified", $ins->Name);
    }

    /*
     * Test de validacion simple:
     * 2 campos simples, con valores simples, correctos
     */
    function testValidate1()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            ["title" => "hola",
                "content" => "adios"]
        );
        $this->assertEquals(true, $result->isOk());
    }

    /*
     * Test de validacion simple:
     * 2 campos simples, con un valor incorrecto.
     */
    function testValidate2()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            ["title" => "holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "content" => "adios"]
        );
        $this->assertEquals(false, $result->isOk());
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(1, count($fieldErrors));
        $keys = array_keys($fieldErrors);
        $this->assertEquals("title", $keys[0]);
        $keys = array_keys($fieldErrors[$keys[0]]);
        $this->assertEquals('lib\model\types\_StringException::TOO_LONG', $keys[0]);
    }

    /*
     * Test de validacion simple, con una relacion incorrecta. Debe ejecutarse la query y validar que el objetivo existe.
     *
     */
    function testValidate3()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            ["title" => "hola",
                "content" => "adios",
                "creator_id" => 5000
            ]
        );
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(false, $result->isOk());
        $this->assertEquals(1, count($fieldErrors));
        $keys = array_keys($fieldErrors);
        $this->assertEquals("creator_id", $keys[0]);
        $keys = array_keys($fieldErrors[$keys[0]]);
        $this->assertEquals('lib\model\types\BaseTypeException::INVALID', $keys[0]);
    }

    /*
     * Test de validacion con path : Establecimiento de un valor nuevo.
     */
    function testValidate4()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            [
                "title" => "hola",
                "content" => "adios",
                "creator_id/Name" => "Juanin"
            ]
        );
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(true, $result->isOk());

    }

    /*
     *   Validacion de captura de errores de modelo padre
     */
    function testValidate4_1()
    {
        $this->init();
        $ins = new \model\tests\User\Admin();
        $result = $ins->__validateArray(
            [
                "Name" => "holaholaholaholaholaholaholaholaholaholaholaholaholaholaholahola",
                "adminrole" => "Normal"
            ]
        );
        $this->assertEquals(false, $result->isOk());
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(1, count($fieldErrors));
        $keys = array_keys($fieldErrors);
        $this->assertEquals("Name", $keys[0]);
        $keys = array_keys($fieldErrors[$keys[0]]);
        $this->assertEquals('lib\model\types\_StringException::TOO_LONG', $keys[0]);
    }

    /*
 *   Validacion de captura de errores de modelo hijo
 */
    function testValidate4_2()
    {
        $this->init();
        $ins = new \model\tests\User\Admin();
        $result = $ins->__validateArray(
            [
                "Name" => "hola",
                "adminrole" => "NoExiste"
            ]
        );
        $this->assertEquals(false, $result->isOk());
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(1, count($fieldErrors));
        $keys = array_keys($fieldErrors);
        $this->assertEquals("adminrole", $keys[0]);
        $keys = array_keys($fieldErrors[$keys[0]]);
        $this->assertEquals('lib\model\types\BaseTypeException::INVALID', $keys[0]);
    }


    /*
     * Test de validacion con path 2: Establecimiento de una relacion, y modificacion del campo relacionado.
     */
    function testValidate5()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            [
                "title" => "hola",
                "content" => "adios",
                "creator_id" => 1,
                "creator_id/Name" => "Juanin"
            ]
        );
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(true, $result->isOk());
    }

    /*
     * Test de validacion con path 3: Establecimiento de una relacion inversa,con campos ok.
     */
    function testValidate6()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            [
                "comments" => [
                    [
                        "id_user" => 2,
                        "title" => "Primer Comentario path",
                        "content" => "Contenido Primer Comentario path"
                    ],
                    [
                        "id_user" => 2,
                        "title" => "Segundo Comentario path",
                        "content" => "Segundo Comentario path"
                    ],
                ]
            ]
        );
        $fieldErrors = $result->getFieldErrors();
        $this->assertEquals(true, $result->isOk());
    }

    /*
     * Test de validacion con path 4: Establecimiento de una relacion inversa,con campos erroneos.
     */
    function testValidate7()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->__validateArray(
            [
                "comments" => [
                    [
                        "id_user" => 2,
                        "title" => "Primer Comentario pathPrimer Comentario pathPrimer Comentario pathPrimer Comentario path",
                        "content" => "Contenido Primer Comentario path"
                    ],
                    [
                        "id_user" => 2,
                        "title" => "Segundo Comentario path",
                        "content" => "Segundo Comentario path"
                    ],
                ]
            ]
        );


        $this->assertEquals(false, $result->isOk());
        $fieldErrors = $result->getFieldErrors();
        $keys = array_keys($fieldErrors);
        $this->assertEquals("title", $keys[0]);
        $subKeys = array_keys($fieldErrors[$keys[0]]);
        $this->assertEquals('lib\model\types\_StringException::TOO_LONG', $subKeys[0]);
    }

    /*
     *  Test de guardado simple
     *
     */
    function testArrLoad1()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->loadFromArray(
            ["title" => "hola",
                "content" => "adios"], false, false
        );
        $id = $ins->id;
        $this->assertEquals(11, $id);
        $ins2 = new \model\tests\Post();
        $ins2->id = $id;
        $ins2->loadFromFields();
        $this->assertEquals("adios", $ins2->content);

    }

    /*
     * Test de guardado a traves de modelo extendido.
     *
     */
    function testArrLoad2()
    {
        $this->init();
        $ins = new \model\tests\User\Admin();
        $result = $ins->loadFromArray(
            ["adminrole" => "Master",
                "Name" => "Juanin"
            ], false, false
        );
        $id = $ins->id;

        $this->assertEquals(5, $id);
        $ins2 = new \model\tests\User\Admin();
        $ins2->id = $id;
        $ins2->loadFromFields();
        $this->assertEquals(5, $ins2->id);
        $role = $ins2->{"*adminrole"}->getLabel();
        $this->assertEquals("Master", $role);


        $ins2 = new \model\tests\User();
        $ins2->id = $id;
        $ins2->loadFromFields();
        $this->assertEquals(5, $ins2->id);
        $name = $ins2->Name;
        $this->assertEquals("Juanin", $name);
    }

    /*
     * Test de guardado con path : Establecimiento de un valor nuevo.
     */
    function testArrLoad4()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->loadFromArray(
            [
                "title" => "hola",
                "content" => "adios",
                "creator_id/Name" => "Juanin"
            ], false, false
        );
        $id = $ins->id;
        $cid = $ins->{"!creator_id"};
        $ins2 = new \model\tests\User();
        $ins2->id = $cid;
        $ins2->loadFromFields();
        $this->assertEquals(5, $ins2->id);
        $name = $ins2->Name;
        $this->assertEquals("Juanin", $name);
    }

    /*
     * Test de insercion con path 2: Establecimiento de una relacion, y modificacion del campo relacionado.
     */
    function testArrLoad5()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->loadFromArray(
            [
                "title" => "hola",
                "content" => "adios",
                "creator_id" => 1,
                "creator_id/Name" => "Juanin"
            ], false, false
        );
        $cid = $ins->{"!creator_id"};
        $ins2 = new \model\tests\User();
        $ins2->id = $cid;
        $ins2->loadFromFields();
        $this->assertEquals(1, $ins2->id);
        $name = $ins2->Name;
        $this->assertEquals("Juanin", $name);
    }

    function testAlias2()
    {
        $this->init();
        $post = new \model\tests\Post();
        $post->title = "hola";
        $post->comments[0]->title = "-Nuevo-";
        $post->save();
        $id = $post->id;
        $this->assertEquals(11, $id);

        $post = new \model\tests\Post();
        $post->id = $id;
        $post->loadFromFields();
        $n = $post->comments->count();
        $this->assertEquals(1, $n);
        $this->assertEquals("-Nuevo-", $post->comments[0]->title);


    }

    /*
     * Test de insercion de alias
     */
    function testArrLoad6()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->loadFromArray(
            [
                "title" => "hola",
                "content" => "adios",
                "creator_id" => 1,
                "comments" => [
                    [
                        "id_user" => 2,
                        "title" => "Primer Comentario path",
                        "content" => "Contenido Primer Comentario path"
                    ],
                    [
                        "id_user" => 2,
                        "title" => "Segundo Comentario path",
                        "content" => "Segundo Comentario path"
                    ],
                ]
            ], false, false
        );
        $nid = $ins->id;
        $ins2 = new \model\tests\Post();
        $ins2->id = $nid;
        $ins2->loadFromFields();
        $this->assertEquals(2, $ins2->comments->count());
    }

    /*
     * Test de insercion de relacion multiple,combinando tanto asignacion de valores
     * existentes, como valores nuevos.
     */
    function testArrLoad7()
    {
        $this->init();
        $ins = new \model\tests\User();
        $ins->loadFromArray([
            "Name" => "Ursulo",
            "roles" => [
                ["id_role" => 2],
                ["id_role" => ["role" => "NuevoRol"]]
            ]
        ], false, false);

        $id = $ins->id;
        $ins2 = new \model\tests\User();
        $ins2->id = $id;
        $ins2->loadFromFields();
        $n = $ins2->roles->count();
        $this->assertEquals(2, $n);
        $this->assertEquals(2, $ins2->roles[0]->{"!id_role"});
        $this->assertEquals("NuevoRol", $ins2->roles[1]->id_role->role);
    }
}

