<?php
namespace lib\tests\model\types\sources;
$dirName=__DIR__."/../../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/sources/ArraySource.php");

use PHPUnit\Framework\TestCase;

class ArraySourceTest extends TestCase
{
    function getSource1($parent)
    {
        return new \lib\model\types\sources\ArraySource($parent,
            [
            "TYPE"=>"Array",
            "LABEL"=>"Label",
            "VALUE"=>"Id",
            "DATA"=>[
                ["Id"=>1,"Label"=>"Primero"],
                ["Id"=>2,"Label"=>"Segundo"],
                ["Id"=>3,"Label"=>"Tercero"],
                ["Id"=>4,"Label"=>"Cuarto"],
            ]
        ]);
    }
    // Este source va a utilizar los valores por defecto de las columnas
    // LABEL y VALUE
    function getSource2($parent)
    {
        return new \lib\model\types\sources\ArraySource($parent,
            [
                "TYPE"=>"Array",
                "DATA"=>[
                    ["VALUE"=>1,"LABEL"=>"Primero"],
                    ["VALUE"=>2,"LABEL"=>"Segundo"],
                    ["VALUE"=>3,"LABEL"=>"Tercero"],
                    ["VALUE"=>4,"LABEL"=>"Cuarto"],
                ]
            ]);
    }
    function getSource3($parent)
    {
        return new \lib\model\types\sources\ArraySource($parent,
            [
                "TYPE"=>"Array",
                "LABEL_EXPRESSION"=>"[%/Label%] [%/SubLabel%]",
                "VALUE"=>"Id",
                "DATA"=>[
                    ["Id"=>1,"Label"=>"Primero","SubLabel"=>"1º"],
                    ["Id"=>2,"Label"=>"Segundo","SubLabel"=>"2º"],
                    ["Id"=>3,"Label"=>"Tercero","SubLabel"=>"3º"],
                    ["Id"=>4,"Label"=>"Cuarto","SubLabel"=>"4º"],
                ]
            ]);
    }

    function getTypedObject1()
    {
        return new \lib\model\BaseTypedObject(
          [
              "FIELDS"=>[
                  "f1"=>["TYPE"=>"String",
                         "SOURCE"=>[
                             "TYPE"=>"Array",
                             "LABEL"=>"Label",
                             "VALUE"=>"Id",
                             "DATA"=>[
                                 ["Label"=>"a","Id"=>"a"],
                                 ["Label"=>"d","Id"=>"b"]
                             ]
                         ]
                      ],
                  "f2"=>["TYPE"=>"String",
                      "SOURCE"=>[
                          "TYPE"=>"Array",
                          "LABEL"=>"Label",
                          "VALUE"=>"Id",
                          "DATA"=>[
                              ["Label"=>"b","Id"=>"b"],
                              ["Label"=>"c","Id"=>"c"]
                          ]
                      ]
                  ],
                  "f3"=>[
                      "TYPE"=>"Integer",
                      "SOURCE"=>[
                          "TYPE"=>"Array",
                          "LABEL_EXPRESSION"=>"[%/Label%] [%/SubLabel%]",
                          "VALUE"=>"Id",

                          "DATA"=>[
                              "a"=>[
                                  "b"=>[
                                  ["Id"=>1,"Label"=>"Primero","SubLabel"=>"1º"]
                                  ],
                                  "c"=>[
                                  ["Id"=>2,"Label"=>"Segundo","SubLabel"=>"2º"]]
                              ],
                              "d"=>
                                  [
                                      "c"=>[
                                      ["Id"=>3,"Label"=>"Tercero","SubLabel"=>"3º"],
                                      ["Id"=>4,"Label"=>"Cuarto","SubLabel"=>"4º"]
                                          ]
                                  ]
                          ],
                          "PATH"=>"/{%#../f1%}/{%#../f2%}"

                      ]
                  ]
              ]
          ]
        );
    }
    function getSource4($parent)
    {
        return new \lib\model\types\sources\ArraySource($parent,
            [
                "TYPE"=>"Array",
                "VALUES"=>["a","b","c","d"]
            ]);
    }
    function testSimple()
    {
        $s=$this->getSource1(null);
        $this->assertEquals(true,$s->contains(1));
    }
    function testSimple2()
    {
        $s=$this->getSource1(null);
        $this->assertEquals(false,$s->contains(5));
    }
    function testSimple3()
    {
        $s=$this->getSource2(null);
        $this->assertEquals(true,$s->contains(1));
    }
    function testSimple4()
    {
        $s=$this->getSource2(null);
        $this->assertEquals(false,$s->contains(5));
    }
    function testSimple5()
    {
        $s=$this->getSource4(null);
        $this->assertEquals(false,$s->containsLabel("x"));
        $this->assertEquals(true,$s->containsLabel("a"));
    }
    function testFieldNames()
    {
        $s1=$this->getSource1(null);
        $s2=$this->getSource2(null);
        $this->assertEquals("Id",$s1->getValueField());
        $this->assertEquals("Label",$s1->getLabelField());
        $this->assertEquals("VALUE",$s2->getValueField());
        $this->assertEquals("LABEL",$s2->getLabelField());
    }
    function testComplexLabel()
    {
        $s3=$this->getSource3(null);
        $data=$s3->getData();
        $l=$s3->getLabel($data[0]);
        $this->assertEquals("Primero 1º",$l);
    }
    function testPath()
    {
        $ins=$this->getTypedObject1();
        $ins->f1="a";
        $ins->f2="b";
        $src=$ins->{"*f3"}->__getSource();
        $d=$src->getData();
        $this->assertEquals(1,count($d));
        $this->assertEquals(1,$d[0]["Id"]);
        $this->assertEquals("Primero",$d[0]["Label"]);
    }
}
