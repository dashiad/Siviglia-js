<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Dictionary.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/TypeSwitcher.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");

use PHPUnit\Framework\TestCase;

class TypeSwitcherTest extends TestCase
{


    function getDefinition1()
    {
        return new \lib\model\types\TypeSwitcher("",[
            "TYPE"=>"TypeSwitcher",
            "TYPE_FIELD"=>"TYPE",
            "ALLOWED_TYPES"=>[
                "String"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "TYPE"=>["TYPE"=>"String"],
                        "MINLENGTH"=>["TYPE"=>"Integer"],
                        "MAXLENGTH"=>["TYPE"=>"Integer"],
                        "REGEXP"=>["TYPE"=>"String"]
                        ]
                ],
                "Integer"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "TYPE"=>["TYPE"=>"String"],
                        "MIN"=>["TYPE"=>"Integer"],
                        "MAX"=>["TYPE"=>"Integer"]
                        ]
                ]
            ]
        ]);
    }
    function getDefinition2()
    {
        return new \lib\model\types\TypeSwitcher("",[
            "TYPE"=>"TypeSwitcher",
            "TYPE_FIELD"=>"TYPE",
            "IMPLICIT_TYPE"=>"ModelReference",
            "ALLOWED_TYPES"=>[
                "String"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "TYPE"=>["TYPE"=>"String"],
                        "MINLENGTH"=>["TYPE"=>"Integer"],
                        "MAXLENGTH"=>["TYPE"=>"Integer"],
                        "REGEXP"=>["TYPE"=>"String"]
                    ]
                ],
                "Integer"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "TYPE"=>["TYPE"=>"String"],
                        "MIN"=>["TYPE"=>"Integer"],
                        "MAX"=>["TYPE"=>"Integer"]
                    ]
                ],
                "ModelReference"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "MODEL"=>["TYPE"=>"String"],
                        "FIELD"=>["TYPE"=>"String"]
                    ]
                ]
            ]
        ]);
    }
    function getDefinition3()
    {
        return new \lib\model\types\TypeSwitcher("",[
            "TYPE"=>"TypeSwitcher",
            "TYPE_FIELD"=>"TYPE",
            "CONTENT_FIELD"=>"DATA",
            "ALLOWED_TYPES"=>[
                "String"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "MINLENGTH"=>["TYPE"=>"Integer"],
                        "MAXLENGTH"=>["TYPE"=>"Integer"],
                        "REGEXP"=>["TYPE"=>"String"]
                    ]
                ],
                "Integer"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "MIN"=>["TYPE"=>"Integer"],
                        "MAX"=>["TYPE"=>"Integer"]
                    ]
                ]
            ]
        ]);
    }



    function testSimple()
    {
        $tp=$this->getDefinition1();
        $tp->setValue(["TYPE"=>"String","MINLENGTH"=>5,"MAXLENGTH"=>10]);
        $v=$tp->TYPE;
        $this->assertEquals("String",$v);
    }
    function testNotValidType()
    {
        $tp=$this->getDefinition1();
        $this->expectException('\lib\model\types\TypeSwitcherException');
        $this->expectExceptionCode(\lib\model\types\TypeSwitcherException::ERR_INVALID_TYPE);
        $tp->setValue(["TYPE"=>"INVALID","MINLENGTH"=>5,"MAXLENGTH"=>10]);
    }
    function testImplicitType()
    {
        $tp=$this->getDefinition2();
        $tp->setValue(["MODEL"=>"One","FIELD"=>"Two"]);
        $this->assertEquals("One",$tp->MODEL);
    }
    function testGet()
    {
        $tp=$this->getDefinition1();
        $tp->setValue(["TYPE"=>"String","MINLENGTH"=>5,"MAXLENGTH"=>10]);
        $v=$tp->getValue();
        $this->assertEquals("String",$v["TYPE"]);
        $this->assertEquals(10,$v["MAXLENGTH"]);
    }
    function testGetImplicit()
    {
        $tp=$this->getDefinition2();
        $tp->setValue(["MODEL"=>"One","FIELD"=>"Two"]);
        $v=$tp->getValue();

        $this->assertEquals("One",$v["MODEL"]);
        $this->assertEquals(false,isset($v["TYPE"]));
    }

    function testContentSimple()
    {
        $tp=$this->getDefinition3();
        $tp->setValue(["TYPE"=>"String","DATA"=>["MINLENGTH"=>5,"MAXLENGTH"=>10]]);
        $val=$tp->DATA;
        $this->assertEquals(5,$val["MINLENGTH"]);
        $val=$tp->getValue();
        $this->assertEquals(5,$val["DATA"]["MINLENGTH"]);
        $d=$tp->TYPE;
        $e=$tp["*DATA"]->MINLENGTH;
        $this->assertEquals("String",$d);
        $this->assertEquals(5,$e);
    }
    function testTypeByType()
    {
        $ts=new \lib\model\types\TypeSwitcher("",[
            "TYPE"=>"TypeSwitcher",
            "ON"=>[
                ["FIELD"=>"f1","IS"=>"Present","THEN"=>"TYPE1"],
                ["FIELD"=>"f2","IS"=>"String","THEN"=>"TYPE2"],
            ],
            "ALLOWED_TYPES"=>[
                "TYPE1"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "f1"=>["TYPE"=>"Integer"],
                        "f3"=>["TYPE"=>"String"]
                    ]
                ],
                "TYPE2"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "f2"=>["TYPE"=>"String"],
                        "f4"=>["TYPE"=>"Integer"]
                    ]
                ]
            ]
        ]);
        $ts->setValue(["f1"=>10,"f3"=>"pepito"]);
        $this->assertEquals("pepito",$ts->f3);
        // Si hago esto, deberia cambiar el tipo por debajo.
        $ts->f2="manolito";
        $fields=$ts->subNode->__getFields();
        $this->assertEquals(true,isset($fields["f4"]));
        $this->assertEquals(false,isset($fields["f3"]));
        $this->assertEquals(null,$ts->f4);
    }
    function testTypeByTypeSimple()
    {
        $ts=new \lib\model\types\TypeSwitcher("",[
            "TYPE"=>"TypeSwitcher",
            "ON"=>[
                ["IS"=>"String","THEN"=>"TYPE1"],
                ["IS"=>"Object","THEN"=>"TYPE2"],
            ],
            "ALLOWED_TYPES"=>[
                "TYPE1"=>[
                    "TYPE"=>"String",
                ],
                "TYPE2"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "f2"=>["TYPE"=>"String"],
                        "f4"=>["TYPE"=>"Integer"]
                    ]
                ]
            ]
        ]);
        $ts->setValue("pepito");
        $this->assertEquals($ts->getValue(),"pepito");
        // Si hago esto, deberia cambiar el tipo por debajo.
        $ts->setValue(["f2"=>"Lala","f4"=>3]);
        $this->assertEquals("Lala",$ts->f2);
    }
}