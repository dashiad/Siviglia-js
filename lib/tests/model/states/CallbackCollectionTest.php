<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/02/2018
 * Time: 15:54
 */

namespace lib\tests\model\states;

$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/states/CallbackCollection.php");

use PHPUnit\Framework\TestCase;
use lib\model\states\CallbackCollection;

class SimpleObject2
{
    public $__five=null;
    function callback_five(){$this->__five="five";}
}
class SimpleObject
{
    public $__one=null;
    public $__two=null;
    public $__three=null;
    public $__four=null;
    public $__s2Instance=null;
    function callback_one(){$this->__one="one";}
    function callback_two($param){$this->__two=$param;}
    function callback_three(){$this->__three="three";}
    function callback_four(){$this->__four="four";}
    function test_ok(){return true;}
    function test_nok(){return false;}
    function getPath($p){$this->__s2Instance=new SimpleObject2();return $this->__s2Instance;}
}

class BaseTypedObjectTest extends TestCase
{
    function getDefinition1()
    {
        $def=array(
            "CALL1"=>array("TYPE"=>"METHOD","METHOD"=>"callback_one"),
            "CALL2"=>array("TYPE"=>"METHOD","METHOD"=>"callback_two","PARAMS"=>array("tested")),
            "CALL3"=>array("TYPE"=>"METHOD","METHOD"=>"callback_three"),
            "CALL4"=>array("TYPE"=>"METHOD","METHOD"=>"callback_five","PATH"=>"a"),
            "TEST1"=>array("TYPE"=>"METHOD","METHOD"=>"test_ok"),
            "TEST2"=>array("TYPE"=>"METHOD","METHOD"=>"test_nok"),
            "PROCESS_1"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("CALL1","CALL2")),
            "PROCESS_2"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("CALL1","CALL10")),
            "PROCESS_3"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("CALL4","PROCESS_1")),
            "TEST_PROCESS"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("TEST1","TEST2"))
        );
        return new CallbackCollection($def);
    }

    /**
     * Test 1: Aplicacion de un array de callbacks que consiste en 1 solo metodo.
     */
    function test1()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $cb->apply(array("CALL1"),$ob);
        $this->assertEquals("one",$ob->__one);
    }
    /**
     * Test 2: Aplicacion de un array de callbacks que consiste en 1 solo metodo, con parametros.
     */
    function test2()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $cb->apply(array("CALL2"),$ob);
        $this->assertEquals("tested",$ob->__two);
    }
    /**
     * Test 3: Aplicacion de un array de callbacks con 2 metodos.
     */
    function test3()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $cb->apply(array("CALL1","CALL2"),$ob);
        $this->assertEquals("tested",$ob->__two);
        $this->assertEquals("one",$ob->__one);
    }
    /**
     * Test 4: Aplicacion de un array de callbacks con 1 proceso.
     */
    function test4()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $cb->apply(array("PROCESS_1"),$ob);
        $this->assertEquals("tested",$ob->__two);
        $this->assertEquals("one",$ob->__one);
    }
    /**
     * Test 5: Aplicacion de un array de callbacks, con 1 proceso que
     * especifica una llamada a metodo no existente.
     */
    function test5()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $this->expectException('\lib\model\states\CallbackCollectionException');
        $this->expectExceptionCode(\lib\model\states\CallbackCollectionException::ERR_NO_SUCH_CALLBACK);
        $cb->apply(array("PROCESS_2"),$ob);
    }
    /**
     * Test 6: LLamada a traves de path
     */
    function test6()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $cb->apply(array("CALL4"),$ob);
        $this->assertEquals("five",$ob->__s2Instance->__five);
    }
    /**
     * Test 7: LLamada tipo test ok
     */
    function test7()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $res=$cb->apply(array("TEST1"),$ob,"TEST");
        $this->assertEquals(true,$res);
    }
    /**
     * Test 7: LLamada tipo test ok
     */
    function test8()
    {
        $cb=$this->getDefinition1();
        $ob=new SimpleObject();
        $res=$cb->apply(array("TEST_PROCESS"),$ob,"TEST");
        $this->assertEquals(false,$res);
    }

}