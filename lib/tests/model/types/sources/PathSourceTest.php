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
class PathSourceTest extends TestCase
{
    var $initialized=false;


    function getContainer()
    {
        $cnt=new \lib\model\types\Container("",[
            "TYPE"=>"Container",
            "FIELDS"=>[
                "f0"=>[
                    "TYPE"=>"String",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../f1/[[KEYS]]"
                    ]
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
        return $cnt;

    }
    function getSource2($parent)
    {
        return new \lib\model\types\sources\DataSource($parent,
            [
                "TYPE"=>"DataSource",
                "MODEL"=>'\model\tests\User',
                "DATASOURCE"=>"FullList",
                "LABEL"=>"[%/Name%] [%/id%]",
                "VALUE"=>"id"
            ]);
    }
    // Este source va a utilizar los valores por defecto de las columnas
    // LABEL y VALUE

    function testSimple()
    {
        $n=$this->getContainer();
        $n->setValue(["f1"=>["aa"=>["q1"=>"uno","q2"=>4],"bb"=>["q1"=>"dos","q2"=>6]]]);
        $s=$n->{"*f0"}->getSource();
        $data=$s->getData();
        $this->assertEquals(2,count($data));
        $this->assertEquals("aa",$data[0]["VALUE"]);
        $this->assertEquals("bb",$data[1]["VALUE"]);
        $this->expectException('\lib\model\types\BaseTypeException');
        $this->expectExceptionCode(\lib\model\types\BaseTypeException::ERR_INVALID);
        $n->f0="hh";
    }
    function testSimple2()
    {
        $n=$this->getContainer();
        $n->setValue(["f1"=>["aa"=>["q1"=>"uno","q2"=>4],"bb"=>["q1"=>"dos","q2"=>6]]]);
        $n->f0="bb";
        $this->assertEquals("bb",$n->f0);
    }
}
