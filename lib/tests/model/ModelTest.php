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
        $ins->{"/creator_id/Name"} = "Lalas";
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
        // Se crea un nuevo comentario en con $ins:
        $ins->posts[0]->comments[]=["title"=>"Uno Nuevo","id_user"=>1];
        // Ahora deberia haber 7 comentarios:
        $cPost=$ins->posts[0];
        $n=$cPost->comments->count();
        $this->assertEquals(7,$n);
        $ins->save();
        // El ultimo comentario, deberia tener el id 12
        $this->assertEquals(13,$cPost->comments[6]->id);
        // Y el titulo es "Uno Nuevo"
        $this->assertEquals("Uno Nuevo",$cPost->comments[6]->title);
        // Y se le ha asignado el id de post 1
        $this->assertEquals(1,$cPost->comments[6]->{"!id_post"});

    }
    // Se continua con los tests de aliases. Se va a probar ahora a establecer
    // los alias, sobreescribiendo los existentes.
    function testAliasRemoval()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $nComments = $ins->comments->count();
        $this->assertEquals(6, $nComments);
        $ins->comments=[];
        $ins->save();
        // Ahora han tenido que borrarse todos los comentarios
        // Esto comprueba que en memoria, la relacion inversa ha actualizado
        // los valores cargados.
        $this->assertEquals(0,$ins->comments->count());

        // Ahora queda asegurarse de que en la base de datos, tambien se han borrado:
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $nComments = $ins->comments->count();
        $this->assertEquals(0, $nComments);
    }
    function testAliasReplacement()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $nComments = $ins->comments->count();
        $this->assertEquals(6, $nComments);
        $comment=new \model\tests\Post\Comment();
        $comment->id_user=2;
        $comment->title="By Code";
        // Se establecen los comentarios, mezclando arrays asociativos e instancias.
        $ins->comments=[
            ["title"=>"Uno Nuevo","id_user"=>1],
            ["title"=>"Dos Nuevos","id_user"=>2],
            $comment
            ];
        $ins->save();
        // Ahora han tenido que borrarse todos los comentarios
        // Esto comprueba que en memoria, la relacion inversa ha actualizado
        // los valores cargados.
        $this->assertEquals(3,$ins->comments->count());

        // Ahora queda asegurarse de que en la base de datos, tambien se han borrado:
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $nComments = $ins->comments->count();
        $this->assertEquals(3, $nComments);
        // Vemos que se ha copiado el campo local (el id del post) en el comentario:
        $c0=$ins->comments[0];
        $post=$c0->{"!id_post"};
        $this->assertEquals(1,$ins->comments[0]->{"!id_post"});
        $this->assertEquals(1,$ins->comments[1]->{"!id_post"});
        $this->assertEquals(1,$ins->comments[2]->{"!id_post"});
        $this->assertEquals("By Code",$ins->comments[2]->title);
    }
    // Cuando se elimina un elemento de una inverse relation. acceder de nuevo a el, deberia dar
    // el elemento siguiente de forma correcta.
    function testAliasDeletion()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        $nComments = $ins->comments->count();
        $this->assertEquals(6, $nComments);
        $id=$ins->comments[0]->id;
        unset($ins->comments[0]);
        $id2=$ins->comments[0]->id;
        $this->assertEquals(1,$id);
        $this->assertEquals(2,$id2);
        $this->assertEquals(5,$ins->comments->count());
    }
    /*
     *  Errores lanzados al establecer valores invalidos para los elementos de la relacion inversa.
     */
    function testAliasError()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $ins->id = 1;
        $ins->loadFromFields();
        // Se introduce un error, con un id_user no valido.

        //TODO: Cuando las excepciones soporten stacking, la excecpcion LOAD_DATA_FAILED deberia ser la
        // ultima, y deberia ser posible obtener la anterior, que deberia ser un fallo del source.

        $thrown=false;
        try {
            $ins->comments = [
                ["title" => "Uno Nuevo", "id_user" => 1000]
            ];
            $ins->save();
        }catch(\Exception $e)
        {
            $thrown=true;
            $this->assertEquals(true,is_a($e,'lib\model\types\BaseTypeException'));
            $this->assertEquals(\lib\model\types\BaseTypeException::ERR_INVALID,$e->getCode());
        }
        $this->assertEquals(true,$thrown);

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
    function testMultipleInverse2()
    {
        // Se va a establecer un objeto complejo, usando la relacion inversa.
        $this->init();
        $ins = new \model\tests\User;
        $ins->id = 1;
        $ins->loadFromFields();
        $current=$ins->roles->count();
        $id1=$ins->roles[0]->id;
        $id1_rol=$ins->roles[0]->id_role->role;
        $ins->roles=[
                ["id_role"=>["role"=>"NuevoRol1"]],
                ["id_role"=>["role"=>"NuevoRol2"]]
        ];
        $ins->save();
        $now=$ins->roles->count();
        $id2=$ins->roles[0]->id;
        $id2_rol=$ins->roles[0]->id_role->role;
        // Valores previos al test
        $this->assertEquals(1,$id1);
        $this->assertEquals(2,$current);
        $this->assertEquals('role1',$id1_rol);
        // Valores posteriores al test
        $this->assertEquals(9,$id2);
        $this->assertEquals('NuevoRol1',$id2_rol);
        $this->assertEquals(1,$ins->roles[0]->{"!id_user"});
        // Y debemos tener todavia 2 elementos
        $this->assertEquals(2,$now);
    }
    function testMultipleInverseDelete()
    {
        // Se va a establecer un objeto complejo, usando la relacion inversa.
        $this->init();
        $ins = new \model\tests\User;
        $ins->id = 1;
        $ins->loadFromFields();
        $current=$ins->roles->count();
        $id1=$ins->roles[0]->id;
        $id1_rol=$ins->roles[0]->id_role->role;
        unset($ins->roles[0]);
        $ins->save();
        $now=$ins->roles->count();
        $id2=$ins->roles[0]->id;
        $id2_rol=$ins->roles[0]->id_role->role;

        // Valores previos al test
        $this->assertEquals(1,$id1);
        $this->assertEquals(2,$current);
        $this->assertEquals('role1',$id1_rol);
        // Valores posteriores al test
        $this->assertEquals(2,$id2);
        $this->assertEquals('role2',$id2_rol);
        // Y ahora tenemos 1 elemento.
        $this->assertEquals(1,$now);
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
        $this->assertEquals("/title", $keys[0]);
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
        $ins->setValidationMode(\lib\model\types\BaseType::VALIDATION_MODE_STRICT);
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
        $this->assertEquals("/creator_id", $keys[0]);
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
        $this->assertEquals("/Name", $keys[0]);
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
        $this->assertEquals("/adminrole", $keys[0]);
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
                "/creator_id/Name" => "Juanin"
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
     * No exactamente un test de validacion, ya que cargamos el valor directamente.
     */
    function testValidate8()
    {
        $this->init();
        $ins = new \model\tests\Post();
        $result = $ins->loadFromArray(
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
        $this->assertEquals("/comments/0/title", $keys[0]);
        $subKeys = array_keys($fieldErrors[$keys[0]]);
        $this->assertEquals('lib\model\types\_StringException::TOO_LONG', $subKeys[0]);
    }
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
        $this->assertEquals("/comments/0/title", $keys[0]);
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
        $this->assertEquals(11,$nid);
        $this->assertEquals(11,$ins2->comments[0]->{"!id_post"});
        $this->assertEquals("Primer Comentario path",$ins2->comments[0]->title);
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
    // Se testea un caso posiblemente problematico de getPath
    function testAliasPath()
    {

        $this->init();
        $ins = new \model\tests\User;
        $ins->id = 1;
        $ins->loadFromFields();

        $p=$ins->getPath("/posts/0/title");
        $p1=$ins->getPath("/posts/0/comments/0/id");
        $this->assertEquals("Post-1",$p);
        $this->assertEquals(1,$p1);


        $p=$ins->getPath("/posts");
        $nPosts = $p->count();
        $this->assertEquals(3, $nPosts);


        $comment = new \model\tests\Post\Comment;
        $comment->id = 1;
        $comment->loadFromFields();
        $p=$comment->getPath("/id_user/Name");
        $this->assertEquals("Comment-user2-post1", $comment->title);
        $this->assertEquals("User2",$p);
    }
    /* Comienzo de tests nuevos: modelos con containers. Hay que probar todo: su serializacion,
      deserializacion, chequeo de campos sucios...*/

    /* Primer test: serializacion simple de un modelo con un campo de tipo container,
      probando las columnas de tipo JSON.
      Comenzamos por un simple campo container.
      Este test comprueba varias cosas:
      1) Gestion de campos sucios
      2) Serializacion de container
      3) Deserializacion de container.
     */
    function testContainerSerialization1()
    {
        $this->init();
        $b=new \model\tests\ClassB();
        $b->C1=[
            "a1"=>["a2"=>"ffff"]
            ];
        $b->save();
        // Vemos que se ha instertado:
        $this->assertEquals(1, $b->id);
        // Se deserializa:
        $b=new \model\tests\ClassB();
        $b->id=1;
        $b->loadFromFields();
        $this->assertEquals("ffff",$b->C1->a1->a2);

        // Se modifica el elemento, esta vez no asignando todo el valor, sino
        // especificamente un campo.
        $b->C1->a1->a2="oooo";
        $b->save();

        // Se crea una nueva instancia para cargar el elemento anterior, y comprobar
        // que se ha guardado correctamente, y que el update ha funcionado.
        $b=new \model\tests\ClassB();
        $b->id=1;
        $b->loadFromFields();
        $this->assertEquals("oooo",$b->C1->a1->a2);
    }
    /* Segundo test: uso de una relacion dentro de un container hijo:*/
    function testDeepRelation()
    {
        $this->init();
        $b=new \model\tests\ClassB();
        $b->C1=[
            "a1"=>["b2"=>1]
        ];
        $b->C1->a1->b2->Name="PROBANDO";
        $b->save();

        // Se deserializa ahora el usuario con id 1, para ver si ha cambiado el nombre.
        $u=new \model\tests\User();
        $u->id=1;
        $u->loadFromFields();
        $name=$u->Name;
        $this->assertEquals("PROBANDO",$name);
    }
    /* Tercer test : asignacion directa de una instancia a una relacion profunda */
    function testDeepRelation2()
    {
        $this->init();
        $u=new \model\tests\User();
        $u->id=1;
        $u->loadFromFields();

        $this->init();
        $b=new \model\tests\ClassB();
        $b->C1=[
            "a1"=>["b2"=>$u]
        ];
        $b->save();

        $b=new \model\tests\ClassB();
        $b->id=1;
        $b->loadFromFields();
        $val=$b->C1->a1->{"!b2"};
        $this->assertEquals(1,$b->C1->a1->{"!b2"});

    }
    /* Cuarto test : Asignacion de un usuario nuevo desde un container interno. */
    function testDeepRelation3()
    {
        $this->init();
        $b=new \model\tests\ClassB();
        $b->C1=[
            "a1"=>["b2"=>["Name"=>"PROBANDO"]]
        ];
        $b->save();

        $id=$b->C1->a1->b2->id;

        // Se deserializa ahora el usuario con id 1, para ver si ha cambiado el nombre.
        $u=new \model\tests\User();
        $u->id=$id;
        $u->loadFromFields();
        $name=$u->Name;
        $this->assertEquals("PROBANDO",$name);
    }
    // Test de completitud de tipo:
    function testContainerRequired()
    {
        $this->init();
        $b=new \model\tests\ClassB();

        $thrown=false;
        try {
            // Este primer intento debe fallar, porque falta un campo requerido.
            $b->C2=["C3"=>["position"=>["LAT"=>1.0]]];
        }catch(\Exception $e)
        {
            $thrown=true;
            $eClass=get_class($e);
            $this->assertEquals('lib\model\types\BaseTypeException',$eClass);
            $this->assertEquals(\lib\model\types\BaseTypeException::ERR_REQUIRED,$e->getCode());
        }
        $this->assertEquals(true,$thrown);
        // Ahora se intenta asignar el campo bien definido..Debe fallar, porque no es editable en este estado
        $thrown=false;
        try {
            $b->C2=["C3"=>["position" => ["LAT" => 1.0,"LON"=>3.0]]];
        }catch(\Exception $e)
        {
            $thrown=true;
            $eClass=get_class($e);
            $this->assertEquals('lib\model\types\BaseTypeException',$eClass);
            $this->assertEquals(\lib\model\types\BaseTypeException::ERR_REQUIRED,$e->getCode());

        }

        // Pasamos el objeto al siguiente estado. Aqui tendria que llamarse al callback.
        // En la definicion de ClassB, hay un TEST de entrada en el estado E2, que requiere que el campo C2->one sea "ffff".
        // Probamos primero a no especificarlo:
        $thrown=false;
        try {
            $b->C2=["state" => "E2"];
        }catch(\Exception $e)
        {
            $thrown=true;
            $eClass=get_class($e);
            $this->assertEquals('lib\model\BaseTypedException',$eClass);
            $this->assertEquals(\lib\model\BaseTypedException::ERR_CANT_CHANGE_STATE,$e->getCode());
        }
        // Se establece ahora el valor correct de C2->one, y se vuelve a intentar el cambio de estado.
        $b->C2=["one"=>"ffff"];
        $b->C2->state="E2";
        // Si no se ha producido una excepcion, es que se ha llamado al callback de test correctamente.
        // Se comprueba que se ha llamado el callback de salida de E1
        $this->assertEquals("ffff",$b->getCbOneCalled());
        // Y que se ha llamado el callback de entrada en E2 (que devuelve el valor por defecto del campo two
        $this->assertEquals("Hola",$b->getCbTwoCalled());

        // Ahora mismo, $b se deberia poder guardar:
        $b->save();
        $this->assertEquals(1,$b->id);
        // En este estado, ya se puede editar la posicion:
        $b->C2=["C3"=>["position" => ["LAT" => 1.0,"LON"=>3.0]]];

        $b->save();
        $c=new \model\tests\ClassB();
        $c->id=1;
        $c->loadFromFields();
        $this->assertEquals(3.0,$c->C2->C3->position->LON);
    }
    // Diferentes pruebas de loadFromArray, para comprobar que los errores se capturan correctamente, sin que se guarde nada en la base de datos.
    function testLoadFromArray1()
    {
        $this->init();
        $b=new \model\tests\ClassB();
        $loadResult=new \lib\model\ModelFieldErrorContainer();
        $b->loadFromArray(["C1"=>["a1"=>["a2"=>"aa","b2"=>1]]],false,false,$loadResult);
        // Vemos que en loadResult hay 1 error
        $errors=$loadResult->getFieldErrors();
        $this->assertEquals(true,isset($errors["/C1/a1/a2"]));
        $keys=array_keys($errors["/C1/a1/a2"]);
        $this->assertEquals("lib\\model\\types\\_StringException::TOO_SHORT",$keys[0]);
        // Intentamos guardar el objeto, y no nos deberia dejar
        $thrown=false;
        try {
            $b->save();
        }catch(\Exception $e)
        {
            $thrown=true;
            $this->assertEquals(true,is_a($e,'lib\model\BaseTypedException'));
            $this->assertEquals(\lib\model\BaseTypedException::ERR_CANT_SAVE_ERRORED_OBJECT,$e->getCode());
        }
        $this->assertEquals(true,$thrown);
        // Arreglamos el problema, y ahora deberia guardar sin problemas.
        $b->{"/C1/a1/a2"}="sss";
        $b->save();
        $h=11;
    }


}

