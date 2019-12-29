<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    function getDefinition1()
    {
        return new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                "two"=>["TYPE"=>"String","DEFAULT"=>"Hola"]
            ]
        ]);
    }
    function getDefinition2()
    {
        return new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                "two"=>["TYPE"=>"String","REQUIRED"=>true],
                "three"=>["TYPE"=>"Integer"],
                "four"=>["TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>true]
            ]
        ]);
    }
    function getDefinition3()
    {
        return new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10],
                "two"=>["TYPE"=>"String"]
            ]
        ]);
    }
    function getDefinition4()
    {
        return new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10,"KEEP_KEY_ON_EMPTY"=>true],
                "two"=>["TYPE"=>"String"]
            ]
        ]);
    }
    function getDefinition5()
    {
        return new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>["TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>10,"KEEP_KEY_ON_EMPTY"=>true],
                "two"=>["TYPE"=>"String"]
            ],
            "DEFAULT"=>[
                "one"=>"1111",
                "two"=>"2222"
            ]
        ]);
    }
    function getDefinition6()
    {
        $instance=new \lib\model\types\Container([
            "TYPE"=>"Container",
            "FIELDS"=>[
                "one"=>[
                    "TYPE"=>"Array",
                    "ELEMENTS"=>[
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "f1"=>[
                                "TYPE"=>"Dictionary",
                                "VALUETYPE"=>[
                                    "TYPE"=>"Container",
                                    "FIELDS"=>[
                                        "q1"=>["TYPE"=>"String"],
                                        "q2"=>["TYPE"=>"Integer"]
                                    ]

                                ]
                            ],
                            "f2"=>[
                                "TYPE"=>"TypeSwitcher",
                                "TYPE_FIELD"=>"Type",
                                "CONTENT_FIELD"=>"Value",
                                "ALLOWED_TYPES"=>[
                                    "String"=>["TYPE"=>"String"],
                                    "Integer"=>["TYPE"=>"Integer"]
                                ]
                            ]
                        ]
                    ]
                ],
                "two"=>["TYPE"=>"String"]
            ]
        ]);
        $instance->setValue(
            [
                "one"=>[
                    [
                        "f1"=>[
                            "k1-1"=>["q1"=>"1","q2"=>2],
                            "k1-2"=>["q1"=>"3","q2"=>4],
                            "k1-3"=>["q1"=>"5","q2"=>6]
                        ],
                        "f2"=>[
                            "Type"=>"String","Value"=>"hola"
                        ]
                    ],
                    [
                        "f1"=>[
                            "k2-1"=>["q1"=>"7","q2"=>8],
                            "k2-2"=>["q1"=>"9","q2"=>10],
                        ],
                        "f2"=>[
                            "Type"=>"String","Value"=>"hola"
                        ]
                    ]
                ],
                "two"=>"Lala"
            ]
        );
        return $instance;
    }


    function testSimple()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>"tres","two"=>"lalas"]);
        $this->assertEquals("tres",$cnt->one);
        $this->assertEquals("lalas",$cnt->two);
    }

    function testDefault()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>"tres"]);
        $this->assertEquals("Hola",$cnt->two);
    }

    function testMissing()
    {
        $cnt=$this->getDefinition2();
        $this->expectException('\lib\model\types\ContainerException');
        $this->expectExceptionCode(\lib\model\types\ContainerException::ERR_REQUIRED_FIELD);
        $cnt->setValue(["one"=>"tres"]);
    }
    function testInvalid()
    {
        $cnt=$this->getDefinition1();
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $cnt->setValue(["one"=>"a","two"=>"lalas"]);
    }
    function testValidateInvalid()
    {
        $cnt=$this->getDefinition1();
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $cnt->validate(["one"=>"a","two"=>"lalas"]);
    }
    function testValidateMissing()
    {
        $cnt=$this->getDefinition2();
        $this->expectException('\lib\model\types\ContainerException');
        $this->expectExceptionCode(\lib\model\types\ContainerException::ERR_REQUIRED_FIELD);
        $cnt->validate(["one"=>"tres"]);
    }
    function testNull()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(null);
        $this->assertEquals(false,$cnt->hasOwnValue());
    }
    function testGetValue()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>"tres","two"=>"lalas"]);
        $tmp=$cnt->getValue();
        $this->assertEquals("tres",$tmp["one"]);
    }
    function testGetEmptyValue()
    {
        $cnt=$this->getDefinition1();
        $tmp=$cnt->getValue();
        $this->assertEquals(null,$tmp);
    }
    function testNullableKeys()
    {
        $cnt=$this->getDefinition2();
        $cnt->setValue(["one"=>"tres","two"=>"lalas"]);
        $tmp=$cnt->getValue();
        $this->assertEquals(false,isset($tmp["three"]));
        $this->assertEquals(null,$tmp["four"]);
    }
    function testDefaultOnNull()
    {
        $cnt=$this->getDefinition1();
        $cnt->setValue(["one"=>null,"two"=>null]);
        $this->assertEquals("Hola",$cnt->two);
    }
    function testNullOnNullValues()
    {
        $cnt=$this->getDefinition3();
        $cnt->setValue(["one"=>null,"two"=>null]);
        $this->assertEquals(false,$cnt->hasOwnValue());
        $this->assertEquals(null,$cnt->getValue());
    }
    function testNotNullOnPreserveNullKey()
    {
        $cnt=$this->getDefinition4();
        $cnt->setValue(["one"=>null,"two"=>null]);
        $this->assertEquals(true,$cnt->hasOwnValue());
        $this->assertEquals(null,$cnt->one);
    }
    function testDefaultValue()
    {
        $cnt=$this->getDefinition5();
        $this->assertEquals("1111",$cnt->one);
    }
    function testPath()
    {
        $cnt=$this->getDefinition6();
        $result=$cnt->getPath("/two");
        $this->assertEquals("Lala",$result);
    }
    function testPath2()
    {
        $cnt=$this->getDefinition6();
        $result=$cnt->getPath("/one/0/f1");
        $keys=array_keys($result);
        $this->assertEquals(true,in_array("k1-1",$keys));
        $this->assertEquals(true,in_array("k1-2",$keys));
        $this->assertEquals(true,in_array("k1-3",$keys));
    }
    function testPath3()
    {
        $cnt=$this->getDefinition6();
        //$result=$cnt->getPath("/one/{/f1/{/q1}}");
        $result=$cnt->getPath("/one/{/f1}");
        $this->assertEquals(2,count($result));
        $keys=array_keys($result[0]);
        $keys1=array_keys($result[1]);
        $this->assertEquals(true,in_array("k1-1",$keys));
        $this->assertEquals(true,in_array("k1-2",$keys));
        $this->assertEquals(true,in_array("k1-3",$keys));
        $this->assertEquals(true,in_array("k2-1",$keys1));
        $this->assertEquals(true,in_array("k2-2",$keys1));
    }
    function testPath4()
    {
        $cnt=$this->getDefinition6();
        $result=$cnt->getPath("/one/{/f1/{/q1}}");

        $this->assertEquals(2,count($result));

        $this->assertEquals("1",$result[0][0]);
        $this->assertEquals("3",$result[0][1]);
        $this->assertEquals("5",$result[0][2]);
        $this->assertEquals("7",$result[1][0]);
        $this->assertEquals("9",$result[1][1]);
    }
    function testPath5()
    {
        $cnt=$this->getDefinition6();
        $result=$cnt->getPath("/one/{/f1/{keys}}");

        $this->assertEquals(2,count($result));
        $this->assertEquals("k1-1",$result[0][0]);
        $this->assertEquals("k1-2",$result[0][1]);
        $this->assertEquals("k1-3",$result[0][2]);
        $this->assertEquals("k2-1",$result[1][0]);
        $this->assertEquals("k2-2",$result[1][1]);
    }
    function testPath6()
    {
        $cnt=$this->getDefinition6();
        $arr=$cnt->{"*one"};
        $cnt2=$arr[0];
        $dict=$cnt2->{"*f1"};
        $cnt3=$dict->{"*k1-1"};
        $field=$cnt3->{"*q1"};
        $v1=$field->parent->getPath("../k1-2/q1");
        $this->assertEquals("3",$v1);
        $v2=$field->parent->getPath("../../f2/Value");
        $this->assertEquals("hola",$v2);
    }
}