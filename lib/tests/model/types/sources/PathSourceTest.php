<?php
namespace lib\tests\model\types\sources;
$dirName=__DIR__."/../../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/sources/PathSource.php");

use PHPUnit\Framework\TestCase;

/**
 * Class DataSourceTest
 * @package lib\tests\model\types\sources
 *
 *
 *   NOTA: ESTE TEST NO ESTA COMPLETADO PORQUE NO TENGO
 *   CLARO QUE ESTE TIPO DE SOURCE LO NECESITEMOS, ESPECIALMENTE
 *   PARA CHEQUEOS
 */
class DataSourceTest extends TestCase
{
    var $initialized=false;

    function getSource1($parent)
    {
        return new \lib\model\types\sources\PathSource($parent,
            [
                "TYPE"=>"PathSource",
                "PATH"=>"../f1/{keys}"
        ]);
    }
    function getContainer()
    {
        $cnt=new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "f0"=>[
                    "TYPE"=>"String"
                    ],
                "f1"=>[
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>[
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "q1"=>["TYPE"=>"String"],
                            "q2"=>["TYPE"=>"Integer"]
                        ]
                    ]
                ]
            ]
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