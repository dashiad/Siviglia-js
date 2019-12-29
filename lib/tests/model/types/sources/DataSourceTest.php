<?php
namespace lib\tests\model\types\sources;
$dirName=__DIR__."/../../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/sources/DataSource.php");

use PHPUnit\Framework\TestCase;

class DataSourceTest extends TestCase
{
    var $initialized=false;
    function getTestPackage()
    {
        return new \lib\tests\model\stubs\model\tests\Package();
    }

    function init()
    {
        if($this->initialized)
            return;
        $this->initialized=true;
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

    function getSource1($parent)
    {
        return new \lib\model\types\sources\DataSource($parent,
            [
                "TYPE"=>"DataSource",
                "MODEL"=>'\model\tests\User',
                "DATASOURCE"=>"FullList",
                "LABEL"=>"Name",
                "VALUE"=>"id"
        ]);
    }
    function getSource2($parent)
    {
        return new \lib\model\types\sources\DataSource($parent,
            [
                "TYPE"=>"DataSource",
                "MODEL"=>'\model\tests\User',
                "DATASOURCE"=>"FullList",
                "LABEL"=>"[%Name%] [%id%]",
                "VALUE"=>"id"
            ]);
    }
    // Este source va a utilizar los valores por defecto de las columnas
    // LABEL y VALUE

    function testSimple()
    {
        $this->init();
        $s=$this->getSource1(null);
        $this->assertEquals(true,$s->contains(1));
    }
    function testSimple2()
    {
        $this->init();
        $s=$this->getSource1(null);
        $this->assertEquals(false,$s->contains(5));
    }
    function testComplexLabel()
    {
        $this->init();
        $s3=$this->getSource2(null);
        $data=$s3->getData();
        $l=$s3->getLabel($data[0]);
        $this->assertEquals("User1 1",$l);
    }
}