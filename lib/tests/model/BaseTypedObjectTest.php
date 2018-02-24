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
    function callback_one(){$this->__one="one";}
    function callback_two($param){$this->__two=$param;}
    function callback_three(){$this->__three="three";}
    function callback_four(){$this->__four="four";}
    function test_ok(){return true;}
    function test_nok(){return false;}
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


}