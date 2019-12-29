<?php

namespace lib\tests\datasource;
$dirName= __DIR__ . "/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(LIBPATH."/storage/Mysql/MysqlDataSource.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;


class MysqlDataSourceTest extends TestCase
{
    var $testResolverIncluded=false;
    var $serializer=null;
    function getTestPackage()
    {
        return new \lib\tests\model\stubs\model\tests\Package();
    }

    function init()
    {
        global $Config;
        $sc=$Config["SERIALIZERS"]["modeltests"]["ADDRESS"];
        if ($this->testResolverIncluded == false) {
            \Registry::getService("model")->addPackage($this->getTestPackage());
            $serService=\Registry::getService("storage");
            $serService->addSerializer("web",

                [
                    "TYPE"=>"Mysql",
                    "NAME"=>"modeltests",
                    "ADDRESS"=>[
                        "host" => $sc["host"],
                        "user" => $sc["user"],
                        "password" => $sc["password"],
                        "database"=>$sc["database"]
                    ]
                ]

            );
            global $Config;

            $conn=new \lib\storage\Mysql\Mysql($Config["SERIALIZERS"]["modeltests"]["ADDRESS"]);
            $conn->connect();
            $conn->importDump(LIBPATH . "/tests/model/stubs/samplemodel.sql");
            $serializer=$serService->getSerializerByName("web");
            $this->serializer=$serializer;
            $this->testResolverIncluded=true;
        }
    }
    function testSimple()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","FullList",$this->serializer);
        $res=$datasource->fetchAll();
        $this->assertEquals(4,$res->count());
    }
    function testSimpleParam()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","FullList",$this->serializer);
        $datasource->id=1;
        $res=$datasource->fetchAll();
        $this->assertEquals(1,$res->count());
    }
    function testSimplePagination()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","FullList",$this->serializer);
        $datasource->__start=0;
        $datasource->__count=2;
        $datasource->__sort="id";
        $datasource->__sortDir="DESC";
        $res=$datasource->fetchAll();
        $n=$res->count();
        $N=$res->fullCount();
        $this->assertEquals(2,$n);
        $this->assertEquals(4,$N);
        $id=$res[0]->id;
        $this->assertEquals(4,$id);
    }
    function testSubDataSources()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","FullList",$this->serializer);
        $datasource->__sort="id";
        $datasource->__sortDir="ASC";
        $res=$datasource->fetchAll();
        $posts=$res[0]->Posts->count();
        $title=$res[1]->Posts[1]->title;
        $this->assertEquals("Post-2-2",$title);
        $posts2=$res[1]->Posts->count();
        $posts3=$res[2]->Posts->count();
        $posts4=$res[3]->Posts->count();
        $this->assertEquals(3,$posts);
        $this->assertEquals(2,$posts2);
        $this->assertEquals(4,$posts3);
        $this->assertEquals(1,$posts4);
    }

    function testParametrizedString()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","FullListParam",$this->serializer);
        $res=$datasource->fetchAll();
        // Nota:este datasource de prueba, si no se especifica un parametro 'Name', pone uno por defecto.
        $this->assertEquals(1,$res->count());
        $this->assertEquals("User1",$res[0]->Name);
    }
    function testParametrizedString2()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","FullListParam",$this->serializer);
        $datasource->Name='User2';
        $res=$datasource->fetchAll();
        // Nota:este datasource de prueba, si no se especifica un parametro 'Name', pone uno por defecto.
        // Aqui lo sobreescribimos.
        $this->assertEquals(1,$res->count());
        $this->assertEquals("User2",$res[0]->Name);
    }
    function testMultipleDataSource()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","Multiple",$this->serializer);
        $datasource->id=1;
        $res=$datasource->fetchAll();
        $n1=$res->Posts->count();
        $this->assertEquals(1,$n1);
        $p=$res->Posts[0];

        $this->assertEquals(1,$p->creator_id);
        $n2=$res->FullList->count();
        $this->assertEquals(1,$n2);
        $this->assertEquals("User1",$res->FullList[0]->Name);
    }
    // Prueba con LOAD_INCLUDES (datasource Multiple2)
    function testMultipleDataSource2()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/User","Multiple2",$this->serializer);
        $datasource->id=1;
        $res=$datasource->fetchAll();
        $n=$res->FullList[0]->Posts->count();
        $this->assertEquals(3,$n);
    }
    function testGroupings()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/Post","Grouped",$this->serializer);
        $res=$datasource->fetchGrouped("creator_id");
        $this->assertEquals(4,$res->count());
        $this->assertEquals(28,$res[0]->likes);
        $this->assertEquals(1,$res[0]->creator_id);
        $this->assertEquals(3,$res[0]->N);
    }
    function testGroupings2()
    {
        $this->init();
        $datasource=\lib\datasource\DataSourceFactory::getDataSource("/model/tests/Post","Grouped",$this->serializer);
        $res=$datasource->fetchGrouped("likes",10);
        $this->assertEquals(5,$res->count());
        // Valores entre 0 y 10
        $this->assertEquals(2,$res[0]->N);
        $this->assertEquals(0,$res[0]->likes);

        // Valores entre 10 y 20
        $this->assertEquals(3,$res[1]->N);
        $this->assertEquals(10,$res[1]->likes);
    }



}
