<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/02/2018
 * Time: 15:54
 */

namespace lib\tests\model;

$dirName=__DIR__."/../../../install/config/CONFIG_test.php";
    //"/../../../install/config/CONFIG_test.php";
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
    public $__enteringCalled=false;
    function check_five($value){if($value=="five")return true;return false;}
    function process_five($value){return "six";}
    function callback_one(){$this->__one="one";}
    function callback_two(){$this->__two="set";}
    function callback_three(){$this->__three="three";}
    function callback_four(){$this->__four="four";}
    function test_ok(){$this->__testedOk=true;return true;}
    function test_nok(){$this->__testedNok=true;return false;}
    function get_seven(){return "seven";}
    function check_eight($value){return strlen($value)>3;}
    function process_eight($value){return $value."##";}
    function enteringState(){$this->__enteringCalled=true;return true;}
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
                            'EDITABLE' => array('one','three','five')
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
                    "TWO"=>array("TYPE"=>"METHOD","METHOD"=>"callback_two"),
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
                            'EDITABLE' => array('two','three'),
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
                    "P_ONE"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("ONE","TWO")),

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
                            //"TESTS"=>array("FAIL_TEST")
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

    function getDefinition6()
    {
        $def=array(
            "FIELDS"=>array(
                "one"=>array("TYPE"=>"String"),
                "arr"=>array("TYPE"=>"Array","ELEMENTS"=>[
                    "TYPE"=>"String"
                ]),
                "dict"=>array("TYPE"=>"Container",
                    "FIELDS"=>[
                        "c1"=>["TYPE"=>"String"],
                        "c2"=>["TYPE"=>"Array",
                               "ELEMENTS"=>[
                                    "TYPE"=>"String"
                               ]
                        ]
                    ]
                    )
            )
        );
        return new SimpleTypedObject($def);
    }
    function getDefinition7()
    {
        /**
         * Ojo, que seven tiene un metodo get_ en SimpleTypedObject, y eight tiene un metodo
         * check_ y process_.
         * El metodo check_eight requiere que la cadena tenga mas de 3 caracteres.
         */
        $def=array(
            "FIELDS"=>array(
                "seven"=>array("TYPE"=>"String"),
                "eight"=>array("TYPE"=>"String")
            )
        );
        return new SimpleTypedObject($def);
    }

    // La definicion 8 es parecida a la definicion 4, pero sin
    // restricciones, para probar la validacion
    function getDefinition8()
    {
            $def=array(
                "FIELDS"=>array(
                    "one"=>array("TYPE"=>"String"),
                    "two"=>array("TYPE"=>"String"),
                    "three"=>array("TYPE"=>"String"),
                    "four"=>array("TYPE"=>"String"),
                    "five"=>array("TYPE"=>"String"),
                    "status"=>array('TYPE' => 'Enum',
                        'VALUES' => array(
                            'None', 'Other', 'Another','Last'
                        ),
                        'DEFAULT' => 'None')
                ),

            );
            return new SimpleTypedObject($def);

    }
    function getDefinition9()
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
                    "P_ONE"=>array("TYPE"=>"PROCESS","CALLBACKS"=>array("ONE","TWO")),
                    "ENTERING_OK"=>array("TYPE"=>"METHOD","METHOD"=>"enteringState"),

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
                                "STATES"=>array("None"=>array("ENTERING_OK")),
                            ),
                            //"TESTS"=>array("FAIL_TEST")
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

        $this->assertEquals("one",$inf[0]->__getFieldName());
        $this->assertEquals(1,count($inf));
    }
    function test2()
    {
        $obj=$this->getDefinition1();
        $obj->one="one";
        $thrown=false;
        try{
            $obj->one="w";
        }catch(\Exception $e)
        {
            $thrown=true;
            $this->assertEquals(true,is_a($e,'lib\model\types\_StringException'));
            $this->assertEquals(\lib\model\types\_StringException::ERR_TOO_SHORT,$e->getCode());
        }
        $this->assertEquals(true,$thrown);
        $this->assertEquals(true,$obj->__isErrored());
        $errored=$obj->getErroredFields();
        $this->assertEquals(1,count($errored));
        $this->assertEquals(true,$obj->{"*one"}->__isErrored());
        $this->assertEquals(true,$obj->{"*one"}->__getError()!==null);
        $this->assertEquals("/one",$errored[0]->__getFieldPath());
        // Le damos ahora un valor valido. Deberia borrarse el error.
        $obj->one="ssss";
        $this->assertEquals(false,$obj->{"*one"}->__isErrored());
        $this->assertEquals(null,$obj->{"*one"}->__getError());
        $this->assertEquals(false,$obj->__isErrored());
    }


    function test3()
    {
        $obj=$this->getDefinition1();
        $result=$obj->__validateArray(array("one"=>"hola"));
        $this->assertEquals(true,$result->isOk());
    }
    function test4()
    {
        $obj=$this->getDefinition1();
        $result=$obj->__validateArray(array("one"=>"h"));
        $this->assertEquals(false,$result->isOk());
        $fieldErrors=$result->getFieldErrors("/one");
        $keys=array_keys($fieldErrors);
        $this->assertEquals(1,count($keys));
        $this->assertEquals('lib\model\types\_StringException::TOO_SHORT',$keys[0]);
    }
    function test5()
    {
        $obj=$this->getDefinition2();
        $this->assertEquals("status",$obj->getStateField());
        $this->assertEquals('None',$obj->{"*status"}->getLabel());
        $obj->three="thr";
        $thrown=false;
        $obj->loadFromArray(array("status" => "None", "three" => "qq", "one" => "lala"), "PHP");
        try {
            $obj->two="hola";
        }catch(\Exception $e) {
            $this->assertEquals(true,is_a($e,'lib\model\BaseTypedException'));
            $this->assertEquals(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,$e->getCode());
            $this->assertEquals(true,$obj->__isErrored());
            $erroredFields=$obj->getErroredFields();
            $this->assertEquals(1, count($erroredFields));
            $error=$erroredFields[0]->__getError();
            $this->assertEquals(true,is_a($error,'lib\model\BaseTypedException'));
            $this->assertEquals(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,$error->getCode());
            $thrown=true;
        }
        $this->assertEquals(true,$thrown);
        $obj->status="Other";


    }
    /* Comprobacion de campos requeridos al cambiar de estado: el estado Another requiere el campo three */
    function test6()
    {
        $obj=$this->getDefinition2();
        $obj->status="Other";
        $thrown=false;
        try{
            $obj->status="Another";
        }
        catch(\Exception $e) {
            $thrown=true;
            $this->assertEquals(true,is_a($e,'lib\model\BaseTypedException'));
            $this->assertEquals(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD,$e->getCode());
            $this->assertEquals(true,$obj->__isErrored());
            $errored=$obj->getErroredFields();
            $this->assertEquals(1,count($errored));
            $this->assertEquals("/three",$errored[0]->__getFieldPath());
            $exception=$errored[0]->__getError();
            $this->assertEquals(true,is_a($exception,'lib\model\BaseTypedException'));
            $this->assertEquals(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD,$exception->getCode());

        }
        $this->assertEquals(true,$thrown);


    }

    function test6_2()
    {
        $obj=$this->getDefinition2();
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
        $res=$obj->__validateArray($data,null);
        $this->assertEquals(false,$res->isOk());
        $ex=$res->getFieldErrors("/two");
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

        $obj->save();

        $data=array(
            "status"=>"Other",
            "two"=>"two"
        );
        $res=$obj->__validateArray($data,null);
        $this->assertEquals(false,$res->isOk());
        $errs=$res->getFieldErrors();
        $keys=array_keys($errs);
        $this->assertEquals(1,count($keys));
        $this->assertEquals("/status",$keys[0]);
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

    /*
     * one
        arr[""]
        dict["c1"],["c2"]
     */

    // Test 1, obtener un path simple
    function testPath0()
    {
        $obj=$this->getDefinition6();
        $obj->one="str_one";

        $val=$obj->getPath("/one");
        $this->assertEquals("str_one",$val);

    }
    function testPath1()
    {
        $obj=$this->getDefinition6();
        $obj->one="str_one";
        $obj->arr=["c1","c2"];
        $obj->dict=[];
        $obj->dict->c1="f1";
        $obj->dict->c2=["first","second"];

        $val=$obj->getPath("/arr/0");
        $this->assertEquals("c1",$val);
        $val2=$obj->getPath("/dict/c1");
        $this->assertEquals("f1",$val2);
        $val3=$obj->getPath("/dict/c2/1");
        $this->assertEquals("second",$val3);
    }
    function testPath2()
    {
        $obj=$this->getDefinition6();
        $obj->one="arr";
        $obj->arr=["c1","c2"];

        $val=$obj->getPath("/{%/one%}/0");
        $this->assertEquals("c1",$val);
    }
    function testFields1()
    {
        $obj=$this->getDefinition6();
        $field=$obj->__getField("one");
        $this->assertEquals(false,$field->is_set());
        $obj->one="hola";
        $this->assertEquals(true,$field->is_set());
    }
    function testCopy()
    {
        $obj=$this->getDefinition6();
        $obj->one="str_one";
        $obj->arr=["c1","c2"];
        $obj->dict=["c1"=>"f1","c2"=>["first","second"]];
        $obj2=$this->getDefinition6();
        $obj2->copy($obj);
        $this->assertEquals("first",$obj2->{"*dict"}->c2[0]);

    }
    function testAsAssociative()
    {
        $obj=$this->getDefinition6();
        $obj->one="str_one";
        $obj->arr=["c1","c2"];
        //$obj->{"*dict"}->c1="f1";
        $obj->dict=["c2"=>["first","second"]];
        $data=$obj->normalizeToAssociativeArray();
        $this->assertEquals("second",$data["dict"]["c2"][1]);
        $this->assertEquals("str_one",$data["one"]);
        $data=$obj->normalizeToAssociativeArray(["dict"=>$obj->__getField("dict")]);
        $this->assertEquals("second",$data["dict"]["c2"][1]);
        $this->assertEquals(false,isset($data["one"]));

    }
    function testValidate()
    {
        $obj=$this->getDefinition8();
        // Es un valor no valido.
        $obj->one="a";
        $obj2=$this->getDefinition4();
        $result=$obj2->__validate(["one"=>$obj->__getField("one")],null,true);
        $this->assertEquals(false,$result->isOk());
        $errors=$result->getFieldErrors();
        $fields=array_keys($errors);
        $this->assertEquals(1,count($fields));
        $this->assertEquals("/one",$fields[0]);
        $exceptions=array_keys($errors[$fields[0]]);
        $this->assertEquals('lib\model\types\_StringException::TOO_SHORT',$exceptions[0]);
        $exceptionDesc=array_keys($errors[$fields[0]][$exceptions[0]]);
        $this->assertEquals(100,$exceptionDesc[0]);
        $exDesc=$errors[$fields[0]][$exceptions[0]][$exceptionDesc[0]];
        $this->assertEquals("a",$exDesc["value"]);
        $this->assertEquals(100,$exDesc["code"]);
    }
    /* Se testea la validacion de un objeto con estado.
       Tenemos un objeto vacio, que queremos moverlo a un estado no valido (Last).
       Ademas, el estado al que va, requiere que el campo three sea no nulo, y lo es.
    */
    function testValidate2()
    {
        $obj=$this->getDefinition8();
        // Es un valor no valido.
        $obj->one="aok1";
        $obj->status="Last";
        $obj2=$this->getDefinition4();
        $result=$obj2->__validate(["one"=>$obj->__getField("one"),"status"=>$obj->__getField("status")]);
        $this->assertEquals(false,$result->isOk());
        $errors=$result->getFieldErrors();
        $fields=array_keys($errors);
        $this->assertEquals(1,count($fields));
        $this->assertEquals(true,in_array("/three",$fields));

        $exceptionThree=array_keys($errors["/three"]);
        $this->assertEquals('lib\model\BaseTypedException::REQUIRED_FIELD',$exceptionThree[0]);
    }
    /* Nueva validacion de un objeto con estado.
       Tenemos un objeto vacio, que queremos moverlo a un estado no valido (Last).
       Ademas, el estado al que va, requiere que el campo three sea no nulo, y lo es.
    */
    function testValidate3()
    {
        $obj=$this->getDefinition8();
        // Es un valor no valido.
        $obj->two="aok1";
        $obj2=$this->getDefinition4();
        $result=$obj2->__validate(["two"=>$obj->__getField("two")]);
        $this->assertEquals(false,$result->isOk());
        $errors=$result->getFieldErrors();
        $fields=array_keys($errors);
        $this->assertEquals(1,count($fields));
        $this->assertEquals(true,in_array("/two",$fields));
        $exceptionStatus=array_keys($errors["/two"]);
        $this->assertEquals('lib\model\BaseTypedException::NOT_EDITABLE_IN_STATE',$exceptionStatus[0]);
    }
    /* Vemos que no podemos movernos de un estado final. */
    function testValidate4()
    {

        $obj2=$this->getDefinition4();
        $obj2->three="aVal";
        $obj2->{"*status"}->setValue("Last");

        $obj2->save();
        $result=$obj2->__validateArray(["status"=>"Another"]);
        $this->assertEquals(false,$result->isOk());
        $errors=$result->getFieldErrors();
        $fields=array_keys($errors);
        $this->assertEquals(1,count($fields));
        // El campo three es requerido en el estado Another. A la vez, es no editable dado el estado de $obj2
        $this->assertEquals(true,in_array("/status",$fields));
        $exceptions=array_keys($errors["/status"]);
        $this->assertEquals('lib\model\BaseTypedException::CANT_CHANGE_FINAL_STATE',$exceptions[0]);
    }

    // Finalmente, al menos 1 validacion ok.
    function testValidate5()
    {
        $obj2=$this->getDefinition4();
        $result=$obj2->__validateArray(["status"=>"Other","two"=>"aaa"]);
        $this->assertEquals(true,$result->isOk());
    }

    function testValidate6()
    {
        $obj2=$this->getDefinition4();
        $res=$obj2->loadFromArray(["status"=>"Other","two"=>"aaa","three"=>"sss"],false,false);
        $obj2->save();
        $result=$obj2->__validateArray(["status"=>"Another"]);
        $this->assertEquals(true,$result->isOk());
    }
    function testValidateSubChangeState()
    {
        $obj2=$this->getDefinition9();
        $obj2->three="lala";
        $obj2->status="Another";
        $this->assertEquals(true,$obj2->__enteringCalled);
    }
    // TESTS A REALIZAR:
    // 1: CONTAINER CON CAMPOS REQUERIDOS: DEBE INICIALIZARSE EL CONTAINER CON TODOS LOS CAMPOS REQUERIDOS
    // ANTES DE ACCEDER A CUALQUIER CAMPO CON EL OPERADOR ->
    // 2: CONTAINER CON CAMPOS REQUERIDOS: LANZAR EXCEPCION SI UN CAMPO REQUERIDO SE PONE A null.
    // 3: CAMPO AUTONUMERICO : NO DEBE PERMITIR EN NINGUN CASO QUE SE LE ESTABLEZCA UN VALOR A null
    function getStateDefinition1()
    {
        return [
            'STATES' => [
                'E1' => [
                    'FIELDS' => ['EDITABLE' => ['one','two','inner']]
                ],
                'E2' => [
                    'ALLOW_FROM'=>["E1"],
                    'FIELDS' => ['EDITABLE' => ['two','three']]
                ],
                'E3' => [
                    'ALLOW_FROM'=>["E2"],
                    'FINAL'=>true,
                    'FIELDS' => ['REQUIRED' => ['three']]]
            ],
            'FIELD' => 'state'
        ];
    }
    function testNestedState()
    {
        $t=new \lib\model\BaseTypedObject([
            "FIELDS"=>[
                "one"=>["TYPE"=>"String"],
                "two"=>["TYPE"=>"String"],
                "state"=>["TYPE"=>"State","VALUES"=>["E1","E2","E3"],"DEFAULT"=>"E1"],
                "inner"=>[
                    "TYPE"=>"Container",
                    "REQUIRED"=>true,
                    "FIELDS"=>[
                        "one"=>["TYPE"=>"String"],
                        "two"=>["TYPE"=>"String"],
                        "three"=>["TYPE"=>"String"],
                        "state"=>["TYPE"=>"State","VALUES"=>["E1","E2","E3"],"DEFAULT"=>"E1"]
                    ],
                    'STATES' => $this->getStateDefinition1()
                ]
            ],
            'STATES' => $this->getStateDefinition1()
        ]);
        // Se intenta asignar un valor no valido del container interno:
        $thrown=false;
        try {
            $t->setValue(["one"=>"aa","state"=>"E1","inner"=>["three"=>"zzz","state"=>"E1"]]);
        }catch(\Exception $e)
        {
            $thrown=true;
            $isErrored=$t->__isErrored();
            $isErrored2=$t->{"*inner"}->__isErrored();
            $isErrored3=$t->inner->{"*three"}->__isErrored();
            $this->assertEquals(true,$isErrored && $isErrored2 && $isErrored3);
            $e1=$t->getErroredFields();
            $path=$e1[0]->__getFieldPath();
            $e2=$t->{"*inner"}->getErroredFields();
            $path2=$e2[0]->__getFieldPath();
            $this->assertEquals("/inner",$path);
            $this->assertEquals("/inner/three",$path2);
        }
        $this->assertEquals(true,$thrown);
        // Arreglamos el campo inner:
        $t->inner=["one"=>"qq","state"=>"E1"];
        $isErrored=$t->__isErrored();
        $isErrored2=$t->{"*inner"}->__isErrored();
        $isErrored3=$t->inner->{"*three"}->__isErrored();
        $this->assertEquals(false, $isErrored || $isErrored2 || $isErrored3);
        // Se pasa el inner al estado E2, y luego a E3, donde deberia dar un error, ya que three es requerido
        $t->inner->state="E2";
        $thrown=false;
        try {
            $t->inner->state="E3";
        }catch(\Exception $e)
        {
            $thrown=true;
            $this->assertEquals(true,is_a($e,'lib\model\BaseTypedException'));
            $this->assertEquals(\lib\model\BaseTypedException::ERR_REQUIRED_FIELD,$e->getCode());
            $isErrored=$t->__isErrored();
            $isErrored2=$t->{"*inner"}->__isErrored();
            $isErrored3=$t->inner->{"*three"}->__isErrored();
            $e1=$t->getErroredFields();
            $path=$e1[0]->__getFieldPath();
            $e2=$t->{"*inner"}->getErroredFields();
            $path2=$e2[0]->__getFieldPath();
            $this->assertEquals("/inner",$path);
            $this->assertEquals("/inner/three",$path2);
        }
        $this->assertEquals(true,$thrown);

    }

}
