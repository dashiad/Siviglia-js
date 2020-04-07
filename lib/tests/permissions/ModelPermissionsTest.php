<?php

namespace lib\tests\permissions;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/autoloader.php");
include_once(LIBPATH."/startup.php");

include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use BASE_SITE\Box as Box;

use lib\model\permissions\PermissionsManager;
use PHPUnit\Framework\TestCase;
use \model\web\Job;
use \model\web\Worker;

class ModelPermissionsTest extends TestCase
{
    var $testResolverIncluded = false;


    function getTestPackage()
    {
        return new \lib\tests\permissions\stubs\model\tests\Package();
    }

    function init()
    {
        if ($this->testResolverIncluded == false) {
            $modelService=\Registry::getService("model");
            $modelService->addPackage($this->getTestPackage());
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
            $serializer = $serService->getSerializerByName("modeltests");

            $conn = $serializer->getConnection();
            $conn->importDump(__DIR__ . "/stubs/samplemodel.sql");
            $this->testResolverIncluded = true;

            // Se inicializan los permisos:
            $permsManager=\Registry::getService("permissions");
            $permsManager->uninstall();
            // Se crea la tabla de usuarios
            $user1=$modelService->getModel("/model/web/WebUser");
            $serializer->createStorage($user1);

            $permsManager->install();
            // Creamos 2 usuarios de prueba:

            $user1->LOGIN="User1";
            $user1->PASSWORD="User1";
            $user1->save();
            $user2=$modelService->getModel("/model/web/WebUser");
            $user2->LOGIN="User1";
            $user2->PASSWORD="User1";
            $user2->save();
            return ["manager"=>$permsManager,"user1"=>$user1,"user2"=>$user2];

        }
    }

    function testACL1()
    {
        $values=$this->init();
        $u1=$values["user1"];
        $u2=$values["user2"];
        $perms=$values["manager"];
        // Primera prueba: crear un grupo de usuarios de prueba, aniadiendo al usuario u1
        $perms->addToGroup(array($u1->USER_ID),"/TESTGROUP",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER);

        // Se da permisos de VIEW sobre el modelo /tests/ClassA , a los pertenecientes al grupo TESTGROUP
        $perms->addPermissions(array("GROUP"=>"/TESTGROUP"),array("GROUP"=>"/model/tests/ClassA"),array("ITEM"=>\lib\model\permissions\PermissionsManager::PERMS_VIEW));
        // Se crea una especificacion de permisos de tipo ACL
        $def=[[
            "TYPE"=>"ACL",
            "REQUIRES"=>["ITEM"=>\lib\model\permissions\PermissionsManager::PERMS_VIEW],
            "ON"=>"/model/tests/ClassA"
        ]];


        // El usuario 1 puede acceder:
        $this->assertEquals(true,$perms->canAccess($def,$u1));

        // El usuario 2 no puede acceder:
        $this->assertEquals(false,$perms->canAccess($def,$u2));


        // El usuario admin puede acceder
        $modelService=\Registry::getService("model");
        $admin=$modelService->loadModel("/model/web/WebUser",["LOGIN"=>"admin"]);
        $this->assertEquals(true,$perms->canAccess($def,$admin));

        // Se añade el usuario 2 al grupo "God"
        $perms->addToGroup(array($u2->USER_ID),"/AllUsers",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER,true,true);

        // Ahora deberia poder acceder
        $this->assertEquals(true,$perms->canAccess($def,$u2));
    }

    function testACL2()
    {
        $values=$this->init();
        $u1=$values["user1"];
        $u2=$values["user2"];
        $perms=$values["manager"];
        // Primera prueba: crear un grupo de usuarios de prueba, aniadiendo al usuario u1
        $perms->addToGroup(array($u1->USER_ID),"/TESTGROUP",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER);

        // Se da permisos de VIEW sobre TODO EL GRUPO /tests , a los pertenecientes al grupo TESTGROUP
        $perms->addPermissions(
            array("GROUP"=>"/TESTGROUP"),
            array("GROUP"=>"/model/tests"),
            array("ITEM"=>\lib\model\permissions\PermissionsManager::PERMS_VIEW)
        );

        // se le quitan los permisos de VIEW a un cierto modelo de /tests (ClassB)
        // especificamente al usuario 1
        $perms->addPermissions(
            array("ITEM"=>$u1),
            array("GROUP"=>"/model/tests/ClassB"),
            array("ITEM"=>\lib\model\permissions\PermissionsManager::PERMS_VIEW),
            0 // ESTE ULTIMO PARAMETRO SIGNIFICA QUE EN VEZ DE PERMITIR, ESTAMOS NEGANDO LOS PERMISOS.
        );

        // Se crea una especificacion de permisos de tipo ACL,
        // que requiere permisos en un modelo (ClassA) del paquete tests

        $def=[[
            "TYPE"=>"ACL",
            "REQUIRES"=>["ITEM"=>\lib\model\permissions\PermissionsManager::PERMS_VIEW],
            "ON"=>"/model/tests/ClassA"
        ]];

        // El usuario 1 puede acceder:
        $this->assertEquals(true,$perms->canAccess($def,$u1));

        // Pero no puede acceder a la siguiente especificacion:
        // Se crea una especificacion de permisos de tipo ACL,
        // que requiere permisos en un subgrupo de A.
        $def=[[
            "TYPE"=>"ACL",
            "REQUIRES"=>["ITEM"=>\lib\model\permissions\PermissionsManager::PERMS_VIEW],
            "ON"=>"/model/tests/ClassB"
        ]];
        $this->assertEquals(false,$perms->canAccess($def,$u1));


        // El usuario 2 no puede acceder:
        $this->assertEquals(false,$perms->canAccess($def,$u2));

        // Se agrega el usuario 2 al grupo TESTGROUP:
        $perms->addToGroup(array($u2->USER_ID),"/TESTGROUP",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER);

        // Y ahora debe poder acceder:
        $this->assertEquals(true,$perms->canAccess($def,$u2));

    }

    function testRole1()
    {
        $values=$this->init();
        $u1=$values["user1"];
        $u2=$values["user2"];
        $perms=$values["manager"];
        // Primera prueba: crear un grupo de usuarios de prueba, aniadiendo al usuario u1
        $perms->addToGroup(array($u1->USER_ID),"/TESTGROUP",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER);
        $perms->addToGroup(array($u2->USER_ID),"/TESTGROUP_2",\lib\model\permissions\PermissionsManager::PERM_TYPE_USER);


        // Se crea una especificacion de permisos de tipo ROLE,
        // que requiere permisos en un modelo (ClassA) del paquete tests

        $def=[[
            "TYPE"=> PermissionsManager::PERMISSIONSPEC_ROLE,
            "ROLE"=>"/TESTGROUP"
        ]];
        $def_2=[[
            "TYPE"=>PermissionsManager::PERMISSIONSPEC_ROLE,
            "ROLE"=>"/TESTGROUP_2"
        ]];

        // El usuario 1 puede acceder a $def
        $this->assertEquals(true,$perms->canAccess($def,$u1));
        // Pero no a $def_2
        $this->assertEquals(false,$perms->canAccess($def_2,$u1));

        // Lo contrario para el usuario 2
        $this->assertEquals(false,$perms->canAccess($def,$u2));
        // Pero no a $def_2
        $this->assertEquals(true,$perms->canAccess($def_2,$u2));


        // El usuario admin puede acceder a cualquiera
        $modelService=\Registry::getService("model");
        $admin=$modelService->loadModel("/model/web/WebUser",["LOGIN"=>"admin"]);
        $this->assertEquals(true,$perms->canAccess($def,$admin));
        $this->assertEquals(true,$perms->canAccess($def_2,$admin));


    }
    function testOwner1()
    {
        // En el modelo de prueba, se ha especificado en la definicion de Comments,
        // que el ownership lo define el campo id_post/creator_id, como si los
        // comentarios fueran "propiedad" del creador del Post al que pertenecen.
        // Independientemente de que esto tenga sentido o no, sirve para probar este tipo de permisos.

        // El post con id = 4 tiene como creator_id=2 . Los comentarios 10,11 y 12.
        // Creamos uns instancia del comentario 10, y, partiendo de este comentario, chequeamos
        // permiso de tipo "Ownership".

        $values=$this->init();
        $u1=$values["user1"]; // NOTA, ESTE USUARIO, AUNQUE SE LLAME "user1", ES EL ID 2, DUEÑO DEL POST
        $u2=$values["user2"];
        $perms=$values["manager"];

        $modelService=\Registry::getService("model");
        $comment=$modelService->loadModel("/model/tests/Post/Comment",["id"=>10]);

        $def=[[
            "TYPE"=>PermissionsManager::PERMISSIONSPEC_OWNER
        ]];
        $this->assertEquals(true,$perms->canAccess($def,$u1,$comment));
        $this->assertEquals(false,$perms->canAccess($def,$u2,$comment));

        // Se comprueba que el usuario admin sigue teniendo permisos, porque se
        // comprueba que el usuario sea el dueño, o que tenga el grupo "/AllUsers".
        $modelService=\Registry::getService("model");
        $admin=$modelService->loadModel("/model/web/WebUser",["LOGIN"=>"admin"]);
        $this->assertEquals(true,$perms->canAccess($def,$admin,$comment));

    }


}

