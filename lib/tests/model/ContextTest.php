<?php
namespace lib\tests\model;
$dirName= __DIR__ . "/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH . "/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{
    function createContext1()
    {
        $c1=[
            "a"=>1,
            "b"=>[
                "d"=>2
            ],
            "c"=>[["a"=>4,"b"=>5],["a"=>10,"b"=>20]],
            "d"=>"b",
            "e"=>[
                "b"=>"h"
                ],
            "f"=> ["h"=>35],
            "y"=>"a"
        ];

        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($c1,"/",$ctxStack);
        return $ctxStack;
    }
    function testSimple()
    {
        $ctx=$this->createContext1();
        $path=new \lib\model\PathResolver($ctx,"/a");
        $value=$path->getPath();
        $this->assertEquals(1,$value);
    }
    function testSimple2()
    {
        $ctx=$this->createContext1();
        $path=new \lib\model\PathResolver($ctx,"/c/1/b");
        $value=$path->getPath();
        $this->assertEquals(20,$value);
    }
    function testNested2()
    {
        $ctx=$this->createContext1();
        $path=new \lib\model\PathResolver($ctx,"/{%/y%}");
        $value=$path->getPath();
        $this->assertEquals(1,$value);
    }
    function testNested()
    {
        $ctx=$this->createContext1();
        $path=new \lib\model\PathResolver($ctx,"/c/{%/a%}/b");
        $value=$path->getPath();
        $this->assertEquals(20,$value);
    }
    function testDoubleNested()
    {
        $ctx=$this->createContext1();
        $path=new \lib\model\PathResolver($ctx,"/c/{%/a%}/{%/d%}");
        $value=$path->getPath();
        $this->assertEquals(20,$value);
    }
    function testInnerNested()
    {
        $ctx=$this->createContext1();
        $path=new \lib\model\PathResolver($ctx,"/f{%/e/{%/d%}%}");
        $value=$path->getPath();
        $this->assertEquals(35,$value);
    }
    function testDoubleContext()
    {
        $c1=[
            "a"=>1,
            "b"=>[
                "d"=>2
            ]
        ];
        $c2=[
            "h"=>"d"
        ];

        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($c1,"/",$ctxStack);
        $ctx=new \lib\model\BaseObjectContext($c2,"#",$ctxStack);
        $path=new \lib\model\PathResolver($ctxStack,"/b{%#h%}");
        $value=$path->getPath();
        $this->assertEquals(2,$value);
    }
    // Comienzan los tests basados en BaseType y BaseTypedObjects
    function testBaseTypedObject()
    {
        $x=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "a"=>["TYPE"=>"Integer"]
            ]
        ]);
        $x->loadFromArray(["a"=>1],true);
        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($x,"/",$ctxStack);
        $path=new \lib\model\PathResolver($ctxStack,"/a");
        $value=$path->getPath();
        $this->assertEquals(1,$value);
    }
    function testBaseTypedObject2()
    {
        $x=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "a"=>["TYPE"=>"Integer"],
                "b"=>["TYPE"=>"Array","ELEMENTS"=>["TYPE"=>"String"]]
            ]
        ]);
        $x->loadFromArray(["a"=>2,"b"=>["uno","dos","tres"]],true);
        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($x,"/",$ctxStack);
        $path=new \lib\model\PathResolver($ctxStack,"/b/{%/a%}");
        $value=$path->getPath();
        $this->assertEquals("tres",$value);
    }
    function testBaseTypedObject3()
    {
        $x=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "a"=>["TYPE"=>"Integer"],
                "b"=>["TYPE"=>"Array","ELEMENTS"=>["TYPE"=>"Container","FIELDS"=>["a1"=>["TYPE"=>"String"]]]],
                "c"=>["TYPE"=>"String"]

            ]
        ]);
        $x->loadFromArray(["a"=>1,"b"=>[["a1"=>"uno"],["a1"=>"dos"],["a1"=>"tres"]],"c"=>"a1"],true);
        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($x,"/",$ctxStack);
        $path=new \lib\model\PathResolver($ctxStack,"/b/{%/a%}/{%/c%}");
        $value=$path->getPath();
        $this->assertEquals("dos",$value);
    }
    function testBaseTypedObject4()
    {
        $x=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "a"=>["TYPE"=>"Integer"],
                "b"=>["TYPE"=>"Array","ELEMENTS"=>["TYPE"=>"Container","FIELDS"=>["a1"=>["TYPE"=>"String"]]]]
            ]
        ]);
        $x->loadFromArray(["a"=>1,"b"=>[["a1"=>"uno"],["a1"=>"dos"],["a1"=>"tres"]]],true);
        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($x,"/",$ctxStack);
        $path=new \lib\model\PathResolver($ctxStack,"/b/[[KEYS]]");
        $value=$path->getPath();

        $this->assertEquals(true,is_array($value));
        $this->assertEquals(1,$value[1]);

    }
    function testBaseTypedObject5()
    {
        $x=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "a"=>["TYPE"=>"Integer"],
                "b"=>["TYPE"=>"Dictionary",
                      "VALUETYPE"=>["TYPE"=>"Container","FIELDS"=>["a1"=>["TYPE"=>"String"]]]]
            ]
        ]);
        $x->loadFromArray(["a"=>1,"b"=>["x"=>["a1"=>"uno"],"y"=>["a1"=>"dos"],"z"=>["a1"=>"tres"]]],true);
        $ctxStack=new \lib\model\ContextStack();
        $ctx=new \lib\model\BaseObjectContext($x,"/",$ctxStack);
        $path=new \lib\model\PathResolver($ctxStack,"/b/[[KEYS]]");
        $value=$path->getPath();

        $this->assertEquals(true,is_array($value));
        $this->assertEquals("y",$value[1]);

        $p=$x->getPath("/b/z/a1");
        $this->assertEquals("tres",$p);
    }
    // Estos tests igual no deberian estar aqui, sino en algo relacionado con BaseType.
    function testBaseType1()
    {
        $x=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "a"=>["TYPE"=>"Integer"],
                "b"=>["TYPE"=>"Dictionary",
                    "VALUETYPE"=>["TYPE"=>"Container","FIELDS"=>["a1"=>["TYPE"=>"String"]]]]
            ]
        ]);
        $x->loadFromArray(["a"=>1,"b"=>["x"=>["a1"=>"uno"],"y"=>["a1"=>"dos"],"z"=>["a1"=>"tres"]]],true);

        $f=$x->{"*b"}->{"*x"}->{"*a1"};
        $t=$f->getPath("#../../y/a1");
        $this->assertEquals("dos",$t);

        $f=$x->getPath("/b/x/*a1");
        $h=11;
    }
}
