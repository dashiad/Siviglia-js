<?php
/**
 * Class ArrayReaderCursorTest
 * @package lib\tests\data\Cursor
 *  (c) Smartclip
 */


namespace lib\tests\data\Cursor;
use lib\data\Cursor\ArrayReaderCursor;
use lib\data\Cursor\Cursor;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/autoloader.php");
include_once(LIBPATH."/data/Cursor/ArrayReaderCursor.php");

use PHPUnit\Framework\TestCase;
class ArrayReaderCursorTest extends TestCase
{
    function getTestArray()
    {
        return [
            ["A"=>1,"B"=>2,"C"=>"hola"],
            ["A"=>2,"B"=>12,"C"=>"hello"],
            ["A"=>3,"B"=>22,"C"=>"ciao"]
        ];
    }
    function getTestArrayTypes()
    {
        return [
            "A"=>["TYPE"=>"Integer"],
            "B"=>["TYPE"=>"Integer"],
            "C"=>["TYPE"=>"String"],
        ];
    }
    function test1()
    {
        $t=new ArrayReaderCursor();
        $myC=new Cursor();
        $sum=0;
        $myC->init(["callback"=>function($item)use(& $sum){
            $sum+=$item["A"];
            return $item;
        }]);
        $t->addCursor($myC);
        $t->init(["array"=>$this->getTestArray(),"meta"=>$this->getTestArrayTypes()]);
        $t->produce();
        $t->produce();
        $t->produce();
        $this->assertEquals(6,$sum);
    }

    /*
     *   Test de 3 cursores encadenados.
     *   OJO : EL productor es el array, al que se le encadenan 2 cursores.
     *   - Al productor se le asignan 2 arrays: uno que multiplica por dos, y otro que suma.
     *   - Al que multiplica por dos, se le asigna uno que suma.
     *   - EL primer sumador, sigue dando 6
     *   - El segundo sumador, que esta encadenado al que multiplica por dos, ahora da 12.
     */
    function test2()
    {
        $t=new ArrayReaderCursor();
        $sum=0;
        $sumP=0;
        $myC=new Cursor();
        $myC->init(["callback"=>function($item){
            $item["A"]=$item["A"]*2;
            return $item;
        }]);
        $myC2=new Cursor();
        $myC2->init(["callback"=>function($item)use(& $sumP){
            $sumP+=$item["A"];
            return $item;
        }]);
        $myC3=new Cursor();
        $myC3->init(["callback"=>function($item)use(& $sum){
            $sum+=$item["A"];
            return $item;
        }]);

        $t->addCursor($myC);
        $t->addCursor($myC2);
        $myC->addCursor($myC3);
        $t->init(["array"=>$this->getTestArray(),"meta"=>$this->getTestArrayTypes()]);
        while($t->produce());
        $this->assertEquals(12,$sum);
        $this->assertEquals(6,$sumP);
    }
    function test3()
    {
        $t=new ArrayReaderCursor();
        $sum=0;
        $called=0;
        $myC=new Cursor();
        $myC->init(["callback"=>function($item) use (&$sum){
            $sum+=$item["A"];
            return $item;
        },
            "endCallback"=>function() use (&$called){
            $called=1;
        }
            ]
        );
        $t->addCursor($myC);
        $t->init(["array"=>$this->getTestArray(),"meta"=>$this->getTestArrayTypes()]);
        while($t->produce());
        $this->assertEquals(1,$called);

    }
}
