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
        include_once(LIBPATH . "/tests/model/stubs/SimplePackage.php");
        return new \lib\tests\model\stubs\SimplePackage();
    }

    function init()
    {
        if ($this->testResolverIncluded == false) {
            \Registry::getService("model")->addPackage($this->getTestPackage());
            $serService=\Registry::getService("storage");
            $serService->addSerializer("web",

                [
                    "TYPE"=>"Mysql",
                    "NAME"=>"web",
                    "ADDRESS"=>[
                        "host" => _DB_SERVER_,
                        "user" => _DB_USER_,
                        "password" => _DB_PASSWORD_,
                        "database"=>"modeltests"
                    ]
                ]

            );
            $serializer=$serService->getSerializerByName("web");
            $this->serializer=$serializer;
            $conn=$serializer->getConnection();
            $conn->importDump(LIBPATH . "/tests/model/stubs/samplemodel.sql");
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
        $posts2=$res[1]->Posts->count();
        $posts3=$res[2]->Posts->count();
        $posts4=$res[3]->Posts->count();
        $this->assertEquals(3,$posts);
        $this->assertEquals(2,$posts2);
        $this->assertEquals(3,$posts3);
        $this->assertEquals(1,$posts4);

    }



}