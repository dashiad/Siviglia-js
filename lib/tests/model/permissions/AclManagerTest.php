<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 26/12/2017
 * Time: 23:37
 */

namespace lib\tests\model\permissions;
$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");

use PHPUnit\Framework\TestCase;
use lib\model\permissions\AclManager;

class AclManagerTest extends TestCase
{
    var $acl;
    var $permsArr;
    function firstSetup()
    {
        global $SERIALIZERS;
        $ser = \lib\storage\StorageFactory::getSerializer($SERIALIZERS["default"]);
        //$ser->useDataSpace($SERIALIZERS["web"]["ADDRESS"]["database"]["NAME"]);
        include_once(PROJECTPATH . "/lib/model/permissions/AclManager.php");
        $this->acl = new AclManager($ser);
        $this->acl->uninstall();
        $this->acl->install();

        $this->permsArr = array(
            "axos" => array(
                "Documents" =>
                    array("private" =>
                        array("salaries",
                            "accounting" => array("invoices", "taxes"),
                            "bussiness" => array("showcase", "provider_prices", "client_prices"),
                            "agenda" => array("contacts", "president")),
                        "public" =>
                            array("showcase", "projects_summary")
                    ),
                "Building_access",
                "accounts"
            ),

            "acos" => array(
                "Documents" => array("create", "modify", "destroy", "view"),
                "accounts",
                "access" => array("building")
            ),

            "aros" => array(
                "Workers" => array("Departments" =>
                    array("CEO" => "Alberto",
                        "marketing" => array("Ana", "Luis", "Pablo"),
                        "finances" => array(
                            "accounting" => array("Juan", "Antonio"),
                            "assessment" => array("Juan", "Pedro")
                        ),
                        "projects" => array("Luisa", "Manuel"),
                        "secretary" => array(
                            "executives" => array("Pilar"),
                            "staff" => array("Mercedes"),
                            "Marivi")
                    )),
                "Providers" => array("Uralita", "Pilar", "Luisa"),
                "Clients" => array(
                    "golden" => array("Corte_ingles", "Repsol", "Pilar"),
                    "silver" => array("Fnac",
                        "normal" => array("Marivi"),
                        "pro" => array("Abengoa")),
                    "Renfe",
                    "Tussam"
                )

            )
        );

// Main structure insertion
        $this->acl->createPermissions($this->permsArr["aros"], 0, 0, AclManager::ARO);
        $this->acl->createPermissions($this->permsArr["acos"], 0, 0, AclManager::ACO);
        $this->acl->createPermissions($this->permsArr["axos"], 0, 0, AclManager::AXO);
// Acl insertions
    }

    function secondSetup()
    {
        global $SERIALIZERS;
        $ser = \lib\storage\StorageFactory::getSerializer($SERIALIZERS["default"]);
        //$ser->useDataSpace($SERIALIZERS["web"]["ADDRESS"]["database"]["NAME"]);
        include_once(PROJECTPATH . "/lib/model/permissions/AclManager.php");
        $this->acl = new AclManager($ser);
        $this->acl->uninstall();
        $this->acl->install();


        $this->permsArr = array(
            "axos" => array(
                "AllObjects"=>array(
                    "Sys"=>array(
                        "web"=>array(
                            "webModules"=>array(
                                "Page"=>array()
                            )
                        )
                    )
                )
            ),

            "acos" => array(
                "AllPermissions"=>array(
                    "Sys"=>array(
                        "web"=>array(
                            "webModules"=>array(
                                "Page"=>array("create","view")

                            )
                        )
                    )
                )
            ),

            "aros"=>array(
                "AllUsers"=>array(
                    "Users"=>array(
                        1
                    )
                )
            )
        );

// Main structure insertion
        $this->acl->createPermissions($this->permsArr["aros"], 0, 0, AclManager::ARO);
        $this->acl->createPermissions($this->permsArr["acos"], 0, 0, AclManager::ACO);
        $this->acl->createPermissions($this->permsArr["axos"], 0, 0, AclManager::AXO);
// Acl insertions
    }

    function test1()
    {
        $this->firstSetup();
        // People from finances can create and modify accounting documents
        // Mapping type: aco - aro_group - axo_group
        $id = $this->acl->add_acl(
            array("ITEM" => array("create", "modify","view")),
            array("GROUP" => array("finances")),
            array("GROUP" => array("accounting"))
        );
        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"create"),
                                array("GROUP"=>"Workers","ITEM"=>"Juan"),
                                array("GROUP"=>"Documents","ITEM"=>"invoices"));
        $this->assertEquals(true,$res);

        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"create"),
            array("GROUP"=>"Workers","ITEM"=>"Pablo"),
            array("GROUP"=>"Documents","ITEM"=>"invoices"));
        $this->assertEquals(false,$res);

        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"view"),
            array("GROUP"=>"Workers","ITEM"=>"Juan"),
            array("GROUP"=>"Documents","ITEM"=>"invoices"));
        $this->assertEquals(true,$res);

        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"destroy"),
            array("GROUP"=>"Workers","ITEM"=>"Juan"),
            array("GROUP"=>"Documents","ITEM"=>"taxes"));
        $this->assertEquals(false,$res);

        // Comprobacion sin "item" en el usuario.
        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"create"),
            array("GROUP"=>"finances"),
            array("GROUP"=>"Documents","ITEM"=>"taxes"));
        $this->assertEquals(true,$res);

    }
    function test2()
    {
        $this->firstSetup();
        // CEO has permissions over all documents
// Mapping type: aco - aro - axo_group
        $this->acl->add_acl(array("ITEM"=>array("create","modify","view","destroy")),
            array("ITEM"=>array("Alberto")),
            array("GROUP"=>array("Documents")));
        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"create"),
                                    array("GROUP"=>"Workers","ITEM"=>"Alberto"),
                                    array("GROUP"=>"Documents","ITEM"=>"salaries"));
        $this->assertEquals($res,true); //0
        $res=$this->acl->acl_check(array("GROUP"=>"Documents","ITEM"=>"create"),
            array("GROUP"=>"Workers","ITEM"=>"Juan"),
            array("GROUP"=>"Documents","ITEM"=>"salaries"));
        $this->assertEquals($res,false);

    }

    function test3()
    {
        $this->firstSetup();
        $this->acl->add_acl(array("ITEM" => array("view")),
            array("ITEM" => array("Pilar")),
            array("ITEM" => array("taxes")));

        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Workers", "ITEM" => "Pilar"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, true);

        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "create"),
            array("GROUP" => "Workers", "ITEM" => "Pilar"),
            array("GROUP" => "Documents", "ITEM" => "salaries"));
        $this->assertEquals($res, false);
    }
    function test4()
    {
        $this->firstSetup();
        // Explicit negative acl for debugging purposes.
// Clients doesnt have access to documents.
        $this->acl->add_acl(array("ITEM" => array("view")),
            array("GROUP" => array("Clients")),
            array("GROUP" => array("Documents")), 0);
// A certain client, has access to documents.
        $this->acl->add_acl(array("ITEM" => array("view")),
            array("ITEM" => array("Tussam")),
            array("GROUP" => array("Documents")));

        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Clients", "ITEM" => "Renfe"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, false);

        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Clients", "ITEM" => "Tussam"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, true);
    }
    function test5()
    {
        $this->firstSetup();
        // A certain client sub-group has permission on documents (clients golden)
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("GROUP"=>array("golden")),
            array("GROUP"=>array("Documents")));

// A certain golden client, has no permissions.
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("ITEM"=>array("Repsol")),
            array("GROUP"=>array("Documents")),
            0);
        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Clients", "ITEM" => "Corte_ingles"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, true);

        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Clients", "ITEM" => "Repsol"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, false);
    }
    function test6()
    {
        $this->firstSetup();
        $this->acl->add_acl(array("ITEM"=>array("building")),
            array("GROUP"=>array("Workers")));

// A certain client has permission to access the building
// Mapping type: aco - aro.
        $this->acl->add_acl(array("ITEM"=>array("building")),
                    array("ITEM"=>array("Repsol")));

        $res = $this->acl->acl_check(array("GROUP" => "access", "ITEM" => "building"),
            array("GROUP" => "Workers", "ITEM" => "Ana"),
            null);
        $this->assertEquals($res, true);

        $res = $this->acl->acl_check(array("GROUP" => "access", "ITEM" => "building"),
            array("GROUP" => "Clients", "ITEM" => "Renfe"),
            null);

        $this->assertEquals($res, false);
        $res = $this->acl->acl_check(array("GROUP" => "access", "ITEM" => "building"),
            array("GROUP" => "Clients", "ITEM" => "Repsol"),
            null);

        $this->assertEquals($res, true);
    }
    function test7()
    {
        $this->firstSetup();
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("ITEM"=>array("Pilar")),
            array("ITEM"=>array("taxes")));
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("GROUP"=>array("Workers")),
            array("GROUP"=>array("accounting")),
            0);
        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Workers", "ITEM" => "Pilar"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, true);


    }
    function test8()
    {
        $this->firstSetup();
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("GROUP"=>array("Workers")),
            array("GROUP"=>array("accounting")),
            0);
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("ITEM"=>array("Pilar")),
            array("ITEM"=>array("taxes")));

        $res = $this->acl->acl_check(array("GROUP" => "Documents", "ITEM" => "view"),
            array("GROUP" => "Workers", "ITEM" => "Pilar"),
            array("GROUP" => "Documents", "ITEM" => "taxes"));
        $this->assertEquals($res, true);
    }
    /*
     *
     * @covers AclManager::getRootGroupId
     */
    function testRootGroupId()
    {
        $this->firstSetup();
        $id=$this->acl->getRootGroupId("Workers",AclManager::ARO);
        $this->assertEquals(1,$id);

    }
    /*
     * @covers AclManager::get_group_id
     */
    function testGetGroupId()
    {
        $this->firstSetup();
        $id=$this->acl->get_group_id("Clients","golden",AclManager::ARO);
        $this->assertEquals(13,$id);
    }
    /*
     * @covers AclManager::get_group_parent_id
     */
    function testGetGroupParentId()
    {
        $this->firstSetup();
        $id=$this->acl->get_group_parent_id(13);
        $this->assertEquals(12,$id);
    }
    /*
     * @covers AclManager::getGroupFromPath
     */
    function testGetGroupFromPath()
    {
        $this->firstSetup();
        $id=$this->acl->getGroupFromPath("/Clients/golden");
        $this->assertEquals(13,$id);
    }

    /*
     * @covers AclManager::getGroupFromPath
     */
    function testRemoveGroup()
    {
        $this->firstSetup();
       $gid=$this->acl->add_group("mytest",0,AclManager::ARO);
       $gid1=$this->acl->get_group_id(0,"mytest",AclManager::ARO);

       $this->assertEquals($gid,$gid1);
       $oid=$this->acl->add_object("item1","item1",AclManager::ARO);
       $oid1=$this->acl->get_object("item1");

        $this->assertEquals($oid,$oid1);
       $id=$this->acl->add_group_object($gid,$oid);
       $id1=$this->acl->__itemIdFromGroupAndId($gid,$oid,"item1");
        $this->assertEquals($id,$id1);
       // First test, without reparenting children.
        $this->acl->del_group($gid,FALSE);
        $this->expectExceptionCode(\lib\model\permissions\AclException::ERR_GROUP_DOESNT_EXIST);
        $gid1=$this->acl->get_group_id(0,"mytest",AclManager::ARO);

        // We have to check that the item got deleted.
        $this->expectExceptionCode(\lib\model\permissions\AclException::ERR_ITEM_DOESNT_EXIST);
        $oid1=$this->acl->get_object("item1");
    }

    function testRemoveGroup2()
    {
        $this->firstSetup();
        // First, a root group is created
        $pgid=$this->acl->add_group("mytestParent",0,AclManager::ARO);
        // A child group is added (this one is the one that will be deleted)
        $gid=$this->acl->add_group("mytest",$pgid,AclManager::ARO);
        // A group is added as a child of the previous one
        $gid2=$this->acl->add_group("mytest2",$gid,AclManager::ARO);
        // An item is added to the
        $oid=$this->acl->add_object("item1","item1",AclManager::ARO);
        $id=$this->acl->add_group_object($gid,$oid);
        // Second test, reparenting children.
        $this->acl->del_group($gid,true);
        // We have to check that the item didnt get deleted.

        $oid1=$this->acl->get_object("item1");
        $this->assertEquals($oid1,$oid);

        // We have to check that the item now belongs to the parent group
        $ggids=$this->acl->get_object_groups($oid);
        $this->assertEquals($pgid,$ggids[0]);

        // We have to check that the subgroup now is child of the root group
        $npgid=$this->acl->get_group_parent_id($gid2);
        $this->assertEquals($pgid,$npgid);
    }
    function testDelObject()
    {
        $this->firstSetup();
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("ITEM"=>array("Pilar")),
            array("ITEM"=>array("taxes")));
        $id=$this->acl->get_object("Pilar");
        $ggids=$this->acl->get_object_groups($id);
        $this->assertEquals(3,count($ggids));
        $this->acl->del_object($id);
        //Vemos si el numero de grupos ahora es cero
        $ggids=$this->acl->get_object_groups($id);
        $this->assertEquals(0,count($ggids));
        $this->expectException('\lib\model\permissions\AclException');
        // vemos si el objeto ahora no existe
        $this->expectExceptionCode(\lib\model\permissions\AclException::ERR_ITEM_NOT_FOUND);
        $oid1=$this->acl->get_object("Pilar");
        // Nota: no testea si habia alguna regla directa que usara el item.
    }
    function testGetUserPermissions()
    {
        $this->firstSetup();
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("ITEM"=>array("Pilar")),
            array("ITEM"=>array("taxes")));
        $id=$this->acl->get_object("Pilar");
        $perms=$this->acl->getUserPermissions("Documents","taxes","Pilar","Workers");
        $this->assertEquals(1,count(array_keys($perms)));
        $this->assertEquals(1,$perms["view"]);

    }
    /*
     * @covers AclManager::canAccess
     */
    function testCanAccessPublic()
    {
        $this->firstSetup();
        // Create a stub for the SomeClass class.
        /*$stub = $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();*/
        $user=null;
        $access=$this->acl->canAccess(array('PUBLIC'),$user);
        $this->assertEquals(true,$access);
    }
    function testCanAccessUserModel()
    {
        $this->secondSetup();
        $user=$this->getMockBuilder('\model\web\WebUser')
                ->disableOriginalConstructor()
                ->getMock();
        $user->method("getId")->willReturn(1);

        $model=$this->getMockBuilder('\lib\model\BaseModel')
            ->disableOriginalConstructor()
            ->getMock();
        $model->method("getState")->willReturn(null);
        $model->method("__getObjectName")->willReturn("Page");
        $model->method("__getKeys")->will($this->returnCallback(function(){
            return new class {public function get(){return array(1);}};
        }));


        $this->acl->add_acl(array("GROUP"=>"Page","ITEM"=>array("view")),
            array("GROUP"=>"Users","ITEM"=>array("1")),
            array("GROUP"=>"Page"));
        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(true,$res);
        $res=$this->acl->canAccess(array("create"),$user,$model);
        $this->assertEquals(false,$res);
    }
    function testResolveAccessId()
    {
        $this->secondSetup();
        $id = $this->acl->resolveAccessIds(null, array(
            "ITEM" => "create"
        ), null);
        $this->assertEquals(true, is_array($id));
        $this->assertEquals(true, isset($id["aco"]));
        $this->assertEquals(true, isset($id["aco"]["ITEM"]));
        $this->assertEquals(2, $id["aco"]["ITEM"]);

        $id = $this->acl->resolveAccessIds(null, array(
            "GROUP" => "/AllPermissions/Sys/web/webModules/Page"
        ), null);
        $this->assertEquals(true, is_array($id));
        $this->assertEquals(true, isset($id["aco"]));
        $this->assertEquals(true, isset($id["aco"]["GROUP"]));
        $this->assertEquals(7, $id["aco"]["GROUP"]);
    }
    function testResolveAccessId2()
    {
        $this->secondSetup();
        // Se prueba un objeto no existente.
        $this->expectException('\lib\model\permissions\AclException');
        // vemos si el objeto ahora no existe
        $this->expectExceptionCode(\lib\model\permissions\AclException::ERR_ITEM_NOT_FOUND);
        $id = $this->acl->resolveAccessIds(null, array(
            "ITEM" => "delete"
        ), null);
    }

    function testResolveAccessId3()
    {
        $this->secondSetup();
        // Se prueba un objeto no existente en ese path
        $id=$this->acl->resolveAccessIds(null,null,array(
            "GROUP"=>"Sites",
            "CREATE"=>true,
            "CREATEPATH"=>"/AllObjects/Sys/web/webModules"
        ));
        $gId=$id["axo"]["GROUP"];

        $nGid=$this->acl->getGroupFromPath("/AllObjects/Sys/web/webModules/Sites",AclManager::AXO);
        $this->assertEquals($gId,$nGid);
    }

    function testGetModulePath()
    {
        $this->secondSetup();
        $reflection = new \ReflectionClass(get_class($this->acl));
        $method = $reflection->getMethod("getModulePath");
        $method->setAccessible(true);

        $path=$method->invokeArgs($this->acl, array("/model/web/Sites"));
        $this->assertEquals("/AllObjects/Sys/web/webModules/Sites",$path);
    }
    function testAddPermissionOverModule()
    {
        $this->secondSetup();


        $user=$this->getMockBuilder('\model\web\WebUser')
            ->disableOriginalConstructor()
            ->getMock();
        $user->method("getId")->willReturn(1);

        $model=$this->getMockBuilder('\lib\model\BaseModel')
            ->disableOriginalConstructor()
            ->getMock();
        $model->method("getState")->willReturn(null);
        $model->method("__getObjectName")->willReturn("Page");
        $model->method("__getKeys")->will($this->returnCallback(function(){
            return new class {public function get(){return array(1);}};
        }));
        $this->acl->addPermissionOverModule("view",1,"Page");

        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(true,$res);
    }
    // This test checks that a previously given permission over a certain module, is revoked.
    function testRemovePermissionsOverModule()
    {
        $this->secondSetup();


        $user=$this->getMockBuilder('\model\web\WebUser')
            ->disableOriginalConstructor()
            ->getMock();
        $user->method("getId")->willReturn(1);

        $model=$this->getMockBuilder('\lib\model\BaseModel')
            ->disableOriginalConstructor()
            ->getMock();
        $model->method("getState")->willReturn(null);
        $model->method("__getObjectName")->willReturn("Page");
        $model->method("__getKeys")->will($this->returnCallback(function(){
            return new class {public function get(){return array(1);}};
        }));
        $this->acl->addPermissionOverModule("view",1,"Page");

        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(true,$res);
        $this->acl->removePermissionOverModule("view",1,"/AllObjects/Sys/web/webModules/Page");
        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(false,$res);
    }
    // This test checks that if permission over all modules is given, but one module is revoked, it still
    // gives the right results.
    function testRemovePermissionsOverModule2()
    {
        $this->secondSetup();


        $user=$this->getMockBuilder('\model\web\WebUser')
            ->disableOriginalConstructor()
            ->getMock();
        $user->method("getId")->willReturn(1);

        $model=$this->getMockBuilder('\lib\model\BaseModel')
            ->disableOriginalConstructor()
            ->getMock();
        $model->method("getState")->willReturn(null);
        $model->method("__getObjectName")->willReturn("Page");
        $model->method("__getKeys")->will($this->returnCallback(function(){
            return new class {public function get(){return array(1);}};
        }));
        $this->acl->add_acl(array("ITEM"=>array("view")),
            array("ITEM"=>array(1)),
            array("GROUP"=>array("webModules")));
        
        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(true,$res);
        
        $this->acl->removePermissionOverModule("view",1,"/AllObjects/Sys/web/webModules/Page");
        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(false,$res);
        $id=$this->acl->resolveAccessIds(null,null,array(
            "GROUP"=>"Sites",
            "CREATE"=>true,
            "CREATEPATH"=>"/AllObjects/Sys/web/webModules"
        ));
        
        // Now, we create a model to check the access to Sites work
        $model=$this->getMockBuilder('\lib\model\BaseModel')
            ->disableOriginalConstructor()
            ->getMock();
        $model->method("getState")->willReturn(null);
        $model->method("__getObjectName")->willReturn("Sites");
        $model->method("__getKeys")->will($this->returnCallback(function(){
            return new class {public function get(){return array(1);}};
        }));
        $res=$this->acl->canAccess(array("view"),$user,$model);
        $this->assertEquals(true,$res);
        
    }

    function testGetAccessDetails()
    {
        $this->secondSetup();
        // Primer test: un permiso dado.
        $this->acl->addPermissionOverModule("view", 1, "Page");
        $res = $this->acl->getAccessDetails("Page", null, "view", 1, $userGroup = AclManager::DEFAULT_USER_GROUP);
        $this->assertEquals(true, $res[0]);
        $this->assertEquals(0, count($res[1]));

        // Segundo test: un permiso no dado:
        $res = $this->acl->getAccessDetails("Page", null, "create", 1, $userGroup = AclManager::DEFAULT_USER_GROUP);
        $this->assertEquals(false, $res[0]);
        $this->assertEquals(0, count($res[1]));

        // Tercer test: damos un permiso posterior sobre "view": debe tener prioridad sobre el primero
        $this->acl->removePermissionOverModule("view", 1, "/AllObjects/Sys/web/webModules/Page");
        $res = $this->acl->getAccessDetails("Page", null, "view", 1, $userGroup = AclManager::DEFAULT_USER_GROUP);
        $this->assertEquals(false, $res[0]);
        $this->assertEquals(0, count($res[1]));

        // Cuarto test: damos un permiso sobre "view" a 1 pagina especifica:
        $oid = $this->acl->add_object("Page", "100", AclManager::AXO);
        $gid = $this->acl->getGroupFromPath("/AllObjects/Sys/web/webModules/Page", AclManager::AXO);
        $id = $this->acl->add_group_object($gid, $oid);

        $this->acl->add_acl(array("GROUP" => "Page", "ITEM" => array("view")),
            array("GROUP" => "Users", "ITEM" => array("1")),
            array("GROUP" => "Page", "ITEM" => array("100")));
        $res = $this->acl->getAccessDetails("Page", null, "view", 1, $userGroup = AclManager::DEFAULT_USER_GROUP);
        $this->assertEquals(false, $res[0]);
        $this->assertEquals(1, count($res[1]));
        $this->assertEquals("100", $res[1][0]);
    }
    function testGetAccessDetails2()
    {
        $this->secondSetup();
  // Quinto test, volvemos a dar permisos sobre "view"
        $this->acl->addPermissionOverModule("view",1,"Page");
        $res=$this->acl->getAccessDetails("Page", null, "view", 1, $userGroup = AclManager::DEFAULT_USER_GROUP);
        $this->assertEquals(true,$res[0]);
        $this->assertEquals(0,count($res[1]));

        // Se crea una primera pagina a la que se les da unos permisos que ya tiene
        $oid=$this->acl->add_object("Page","100",AclManager::AXO);
        $gid=$this->acl->getGroupFromPath("/AllObjects/Sys/web/webModules/Page",AclManager::AXO);
        $id=$this->acl->add_group_object($gid,$oid);
        // Se quitan los permisos para esa pagina
        $this->acl->add_acl(array("GROUP"=>"Page","ITEM"=>array("view")),
            array("GROUP"=>"Users","ITEM"=>array("1")),
            array("GROUP"=>"Page","ITEM"=>array("100")));

        // Se crea una segunda pagina, la 101
        $oid=$this->acl->add_object("Page","101",AclManager::AXO);
        $gid=$this->acl->getGroupFromPath("/AllObjects/Sys/web/webModules/Page",AclManager::AXO);
        $id=$this->acl->add_group_object($gid,$oid);
        // Se quitan los permisos para esa pagina
        $this->acl->add_acl(array("GROUP"=>"Page","ITEM"=>array("view")),
            array("GROUP"=>"Users","ITEM"=>array("1")),
            array("GROUP"=>"Page","ITEM"=>array("101")),0);
        // Se obtienen ahora los permisos
        // La 100 no debe salir, ya que coincide con el valor del original.
        $res=$this->acl->getAccessDetails("Page", null, "view", 1, $userGroup = AclManager::DEFAULT_USER_GROUP);

        $this->assertEquals(true,$res[0]);
        $this->assertEquals(1,count($res[1]));
        $this->assertEquals("101",$res[1][0]);
    }


}