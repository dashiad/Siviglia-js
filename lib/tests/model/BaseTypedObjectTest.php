<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/02/2018
 * Time: 15:54
 */

namespace lib\tests\model;

$dirName=__DIR__."/../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");

use PHPUnit\Framework\TestCase;
use lib\model\BaseTypedObject;

class SimpleTypedObject extends BaseTypedObject
{
    public $__one=null;
    public $__two=null;
    public $__three=null;
    public $__four=null;
    public $__five=null;
    public $__testedOk=false;
    public $__testedNok=false;
    function check_five($value){if($value=="five")return true;return false;}
    function process_five($value){return "six";}
    function callback_one(){$this->__one="one";}
    function callback_two($param){$this->__two=$param;}
    function callback_three(){$this->__three="three";}
    function callback_four(){$this->__four="four";}
    function test_ok(){$this->__testedOk=true;return true;}
    function test_nok(){$this->__testedNok=true;return false;}
}

class BaseTypedObjectTest extends TestCase
{
    function getDefinition1()
    {
        $def=array(
            "FIELDS"=>array("one"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4))
        );
        return new BaseTypedObject($def);
    }
    function getDefinition2()
    {
        $def=array(
            "FIELDS"=>array(
                "one"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "two"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "three"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "four"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "five"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "status"=>array('TYPE' => 'State',
                'VALUES' => array(
                    'None', 'Other', 'Another'
                ),
                'DEFAULT' => 'None')
            ),
            'STATES' => array(
                'STATES' => array(
                    'None' => array(
                        'FIELDS' => array(
                            'EDITABLE' => array('one','three')
                        )
                    ),
                    'Other' => array(
                        'ALLOW_FROM'=>array("None"),
                        'FIELDS' => array(
                            'EDITABLE' => array('two'),
                            'FIXED' => array('one'))
                    ),
                    'Another' => array('FIELDS' => array(
                        'EDITABLE' => array('one'),
                        'REQUIRED' => array('three')
                    )
                    ),
                    'Last' => array('FIELDS' => array(
                            'EDITABLE' => array('one'),
                            'REQUIRED' => array('three')
                        )
                    )
                ),
                'FIELD' => 'status',
                'DEFAULT' => 'None'
            )
        );
        return new BaseTypedObject($def);
    }
    function getDefinition3()
    {
        $ob=$this->getDefinition2();
        return new SimpleTypedObject($ob->getDefinition());
    }
    function getDefinition4()
    {

        $def=array(
            "FIELDS"=>array(
                "one"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "two"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "three"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "four"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "five"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "status"=>array('TYPE' => 'State',
                    'VALUES' => array(
                        'None', 'Other', 'Another','Last'
                    ),
                    'DEFAULT' => 'None')
            ),
            'STATES' => array(
                "LISTENER_TAGS"=>array(
                    "ONE"=>array("TYPE"=>"METHOD","METHOD"=>"callback_one"),
                    "TWO"=>array("TYPE"=>"METHOD","METHOD"=>"callback_two","PARAMS"=>array("set")),
                    "THREE"=>array("TYPE"=>"METHOD","METHOD"=>"callback_three"),
                    "FAIL_TEST"=>array("TYPE"=>"METHOD","METHOD"=>"test_nok"),
                    "TEST_OK"=>array("TYPE"=>"METHOD","METHOD"=>"test_ok"),
                    "P_ONE"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("ONE","TWO"))
                ),
                'STATES' => array(
                    'None' => array(
                        "LISTENERS"=>array(
                            "ON_LEAVE"=>array(
                                "STATES"=>array("Other"=>array("ONE")),
                            ),
                            "TESTS"=>array("TEST_OK")
                        ),
                        'FIELDS' => array(
                            'EDITABLE' => array('one','three')
                        )
                    ),
                    'Other' => array(
                        'ALLOW_FROM'=>array("None","Another"),
                        "LISTENERS"=>array(
                            "ON_ENTER"=>array(
                                "STATES"=>array(
                                    "None"=>array("TWO"),
                                    "Another"=>array("THREE")
                                )
                            )
                        ),
                        'FIELDS' => array(
                            'EDITABLE' => array('two'),
                            'FIXED' => array('one'))
                    ),
                    'Another' => array(
                        "LISTENERS"=>array(

                            "TESTS"=>array("FAIL_TEST")
                        ),
                        'FIELDS' => array(
                        'EDITABLE' => array('one'),
                        'REQUIRED' => array('three')
                        )
                    ),
                    'Last' => array(
                        "FINAL"=>1,
                        "LISTENERS"=>array(
                            "ON_ENTER"=>array(
                                "STATES"=>array("None"=>array("THREE","P_ONE")),
                            ),
                            "TESTS"=>array("TEST_OK")
                        ),
                        'FIELDS' => array(
                        'EDITABLE' => array('one'),
                        'REQUIRED' => array('three')
                    )
                    )
                ),
                'FIELD' => 'status',
                'DEFAULT' => 'None'
            )
        );
        return new SimpleTypedObject($def);
    }

    function getDefinition5()
    {

        $def=array(
            "FIELDS"=>array(
                "one"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "two"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "three"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "four"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "five"=>array("TYPE"=>"String","MINLENGTH"=>2,"MAXLENGTH"=>4),
                "status"=>array('TYPE' => 'State',
                    'VALUES' => array(
                        'None', 'Other', 'Another','Last'
                    ),
                    'DEFAULT' => 'None')
            ),
            'STATES' => array(
                "LISTENER_TAGS"=>array(
                    "ONE"=>array("TYPE"=>"METHOD","METHOD"=>"callback_one"),
                    "TWO"=>array("TYPE"=>"METHOD","METHOD"=>"callback_two","PARAMS"=>array("set")),
                    "THREE"=>array("TYPE"=>"METHOD","METHOD"=>"callback_three"),
                    "FAIL_TEST"=>array("TYPE"=>"METHOD","METHOD"=>"test_nok"),
                    "TEST_OK"=>array("TYPE"=>"METHOD","METHOD"=>"test_ok"),
                    "P_ONE"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("ONE","TWO"))
                ),
                'STATES' => array(
                    'None' => array(
                        "LISTENERS"=>array(
                            "ON_LEAVE"=>array(
                                "STATES"=>array(
                                    "Other"=>array("ONE"),
                                    "*"=>array("TWO")
                                ),
                            ),
                            "TESTS"=>array("TEST_OK")
                        ),
                        'FIELDS' => array(
                            'EDITABLE' => array('one','three')
                        )
                    ),
                    'Other' => array(
                        'ALLOW_FROM'=>array("None","Another"),
                        "LISTENERS"=>array(
                            "ON_ENTER"=>array("THREE")
                            ),
                        'FIELDS' => array(
                            'EDITABLE' => array('two'),
                            'FIXED' => array('one'))
                    ),
                    'Another' => array(
                        "LISTENERS"=>array(
                            "ON_ENTER"=>array(
                                "STATES"=>array("None"=>array("THREE","P_ONE")),
                            ),
                            "TESTS"=>array("FAIL_TEST")
                        ),
                        'FIELDS' => array(
                            'EDITABLE' => array('one'),
                            'REQUIRED' => array('three')
                        )
                    ),
                    'Last' => array(
                        "FINAL"=>1,
                        "LISTENERS"=>array(
                            "TESTS"=>array("TEST_OK")
                        ),
                        'FIELDS' => array(
                        'EDITABLE' => array('one'),
                        'REQUIRED' => array('three')
                    )
                    )
                ),
                'FIELD' => 'status',
                'DEFAULT' => 'None'
            )
        );
        return new SimpleTypedObject($def);
    }

    function test1()
    {
        $ob=$this->getDefinition1();
        $ob->one="hola";
        $this->assertEquals("hola",$ob->{"*one"}->getValue());
        $this->assertEquals(true,$ob->isDirty());
        $inf=$ob->getDirtyFields();
        $keys=array_keys($inf);
        $this->assertEquals("one",$keys[0]);
        $this->assertEquals(1,count($keys));
    }
    function test2()
    {
        $obj=$this->getDefinition1();
        $obj->one="one";
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $obj->one="";
    }

    /**
     * Se prueba que funciona el metodo check: debe retornar falso, y lanzar una excepcion.
     */
    function test2_1()
    {
        $obj=$this->getDefinition3();
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_INVALID_VALUE);
        $obj->five="four";
    }

    /**
     * Se prueba que funciona el metodo check y el metodo process.En este caso, check debe devolver true.
     */
    function test2_2()
    {
        $obj=$this->getDefinition3();
        $obj->five="five";
        // El metodo "process" ha debido convertirlo en "six"
        $this->assertEquals("six",$obj->five);
    }
    function test3()
    {
        $obj=$this->getDefinition1();
        $result=$obj->validate(array("one"=>"hola"));
        $this->assertEquals(true,$result->isOk());
    }
    function test4()
    {
        $obj=$this->getDefinition1();
        $result=$obj->validate(array("one"=>"h"));
        $this->assertEquals(false,$result->isOk());
        $fieldErrors=$result->getFieldErrors("one");
        $keys=array_keys($fieldErrors);
        $this->assertEquals(1,count($keys));
        $this->assertEquals('lib\model\types\_StringException::TOO_SHORT',$keys[0]);
    }
    function test5()
    {
        $obj=$this->getDefinition2();
        $this->assertEquals("status",$obj->getStateField());
        $this->assertEquals('None',$obj->getState());
        $obj->three="thr";
        $obj->loadFromArray(array("status"=>"None","three"=>"qq","one"=>"lala"),"PHP");
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE);
        $obj->two="hola";
    }
    function test6()
    {
        $obj=$this->getDefinition2();
        $obj->status="Other";
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_DOUBLESTATECHANGE);
        $obj->status="Another";
    }
    function test6_1()
    {
        $obj=$this->getDefinition2();
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD);
        $obj->status="Another";
    }
    function test6_2()
    {
        $obj=$this->getDefinition2();
        $obj->three="Test";
        $obj->status="Another";
        $this->assertEquals("Another",$obj->{"*status"}->getLabel());
    }
    function test7()
    {
        $obj=$this->getDefinition2();
        $obj->status="Other";
        $obj->cleanDirtyFields();
        $obj->three="Test";
        $obj->status="Another";
        $this->assertEquals("Another",$obj->{"*status"}->getLabel());
    }
    function test8()
    {
        $obj=$this->getDefinition2();
        $obj->three="Hola";
        $obj->status="Another";
        $obj->cleanDirtyFields();
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_CANT_CHANGE_STATE_TO);
        $obj->status="Other";
    }

    /**
     * Test9 : Error al cargar datos, ya que se establece un estado con
     * un campo no editable.
     */
    function test9()
    {
        $obj=$this->getDefinition2();
        $data=array(
            "status"=>"Another",
            "two"=>"two"
        );
        $res=$obj->validate($data,null,"PHP");
        $this->assertEquals(false,$res->isOk());
        $ex=$res->getFieldErrors("two");
        $this->assertEquals(true,isset($ex) && $ex!=null);
        $keys=array_keys($ex);
        $this->assertEquals(1,count($ex));
        $this->assertEquals('lib\model\BaseTypedException::NOT_EDITABLE_IN_STATE',$keys[0]);
    }
    /**
     * Test 10: Error al cargar datos, ya que se intenta ir a un estado
     * no posible desde el estado actual:
     */
    function test10()
    {
        $obj=$this->getDefinition2();
        $obj->three="THR";
        $obj->status="Another";

        $obj->cleanDirtyFields();

        $data=array(
            "status"=>"Other",
            "two"=>"two"
        );
        $res=$obj->validate($data,null,"PHP");
        $this->assertEquals(false,$res->isOk());
        $errs=$res->getFieldErrors();
        $keys=array_keys($errs);
        $this->assertEquals(1,count($keys));
        $this->assertEquals("status",$keys[0]);
        $subKeys=array_keys($errs[$keys[0]]);
        $this->assertEquals('lib\model\BaseTypedException::CANT_CHANGE_STATE_TO',$subKeys[0]);
    }

    /**
     * Test 11: Error, al asignar un estado no valido.
     */
    function test11()
    {
        $obj=$this->getDefinition2();
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_UNKNOWN_STATE);
        $obj->status="NotExistent";
    }
    /**
     * Test 12: Comprobacion de ejecucion de callbacks 1
     */
    function test12()
    {
        $obj=$this->getDefinition2();
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_UNKNOWN_STATE);
        $obj->status="NotExistent";
    }
    /**
     * Test 13: Inicio de testing de callbacks de cambio de estado.
     */
    function test13()
    {
        $obj=$this->getDefinition4();
        $obj->status="Other";
        // Se ha abandonado el estado None, por lo que se ha tenido que ejecutar
        // el LISTENER_TAG ONE, es decir, el callback_one, y la variable __one debe valer "one".
        // Tambien se ha entrado en el estado Other, desde el estado None,
        // por lo que se ha tenido que ejecutar el LISTENER_TAG "TWO"
        $this->assertEquals("one",$obj->__one);
        $this->assertEquals("set",$obj->__two);
        $this->assertEquals(null,$obj->__three);
        $obj->cleanDirtyFields();
        // Se pasa de "Other" a "Another": Debe fallar porque el test_nok se
        // ejecuta, y devuelve false.Se comprueba la excepcion, y que se ha
        // ejecutado el test.
        $obj->three="thr";

        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_CANT_CHANGE_STATE);
        $obj->status="Another";
        $this->assertEquals(true,$obj->__testedNok);
    }

    /**
     * Test 13: Se cambia el estado, y se vuelve al inicial (None), para comprobar
     * que el callback de TEST se ejecuta.
     */
    function test13_2()
    {
        $obj = $this->getDefinition4();
        $obj->status = "Other";
        $obj->cleanDirtyFields();
        $obj->status="None";
        $this->assertEquals(true,$obj->__testedOk);
    }

    /*
     *  Test parecido al anterior,pero pasando de None a Final, para comprobar que se
     * ejecuta el ON_ENTER
     */
    function test14()
    {
        $obj=$this->getDefinition4();
        $obj->three="thr";
        $obj->status="Last";
        // Se ejecuta tanto el tag THREE como el proceso P_ONE
        $this->assertEquals("three",$obj->__three);
        $this->assertEquals("one",$obj->__one);
        $this->assertEquals("set",$obj->__two);
        //Ademas, al haber entrado al estado Last, tiene que haberse ejecutado
        // el TEST_OK, por lo que __testedOk debe ser true
        $this->assertEquals(true,$obj->__testedOk);


    }
    /*
     * Test 15:
     * Comprobacion de que no podemos movernos de un estado Final.
     */
    function test15()
    {
        $obj = $this->getDefinition4();
        $obj->three = "thr";
        $obj->status = "Last";
        $obj->cleanDirtyFields();
        $this->expectException('\lib\model\BaseTypedException');
        $this->expectExceptionCode(\lib\model\BaseTypedException::ERR_CANT_CHANGE_FINAL_STATE);
        $obj->status="None";
        $this->assertEquals($obj->{"*status"}->getLabel(),"Last");
    }
    /*
     * Test 16:
     * Comprobacion de que funcionan la especificacion de callbacks con fallbacks ("*"), y con especificacion
     * de callbacks sin necesidad de especificar estados destino uno a uno.
     * Para eso se usa una nueva definicion.
     */
    function test16()
    {
        $obj=$this->getDefinition5();
        $obj->three="thr";
        $obj->status="Last";
        // Se ha tenido que ejecutar el callback "TWO" via el estado "*"
        $this->assertEquals("set",$obj->__two);
    }
    /*
     * Test 17
     * Comprobacion de que funciona la especificacion de callbacks independientes del estado destino.
     */
    function test17()
    {
        $obj=$this->getDefinition5();
        $obj->three="thr";
        $this->assertEquals(null,$obj->__three);
        $obj->status="Other";
        // Se ha tenido que ejecutar el callback "TWO" via el estado "*"
        $this->assertEquals("three",$obj->__three);
    }

}