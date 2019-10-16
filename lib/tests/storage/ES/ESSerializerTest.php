<?php
/**
 * Class ESSerializerTest
 * @package lib\tests\storage\ES
 *  (c) Smartclip
 */

namespace lib\tests\storage\ES;
$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use lib\model\BaseTypedObject;


class ESSerializerTest extends TestCase
{
    var $serializer=null;
    function getDefaultSerializer()
    {
        if($this->serializer===null)
            $this->serializer=new \lib\storage\ES\ESSerializer(["NAME"=>"MAIN_ES","ES"=>["servers"=>[ES_TEST_SERVER],"port"=>ES_TEST_PORT,"index"=>ES_TEST_INDEX]]);
        return $this->serializer;
    }
    function getSimpleDefinedObject()
    {
        return new \lib\model\BaseTypedObject([
            "INDEXFIELDS"=>["id"],
            "FIELDS"=>[
                "id"=>[
                    "TYPE"=>"Integer"
                ],
                "name"=>[
                    "TYPE"=>"String"
                ],
                "data"=>[
                    "TYPE"=>"Integer"
                ]
    ]
        ]);
    }
    function createTestIndex($obj)
    {
        $ser=$this->getDefaultSerializer();
        try{
            $ser->destroyStorage($obj);
        }catch(\Exception $e)
        {

        }
        $ser->createStorage($obj);
    }
    function testCreateSerializer()
    {
        $obj=$this->getSimpleDefinedObject();
        $this->createTestIndex($obj);
        $ser=$this->getDefaultSerializer();
        $client=$ser->getConnection();
        $this->assertEquals(true,$client->indexExists("testIndex"));
    }
    function testDestroyStorage()
    {
        $obj=$this->getSimpleDefinedObject();
        $this->createTestIndex($obj);
        $ser=$this->getDefaultSerializer();
        $ser->destroyStorage($obj,"testIndex");
        $client=$ser->getConnection();
        $this->assertEquals(false,$client->indexExists("testIndex"));
    }
    function testAdd()
    {
        $obj=$this->getSimpleDefinedObject();
        $obj->id=1;
        $obj->name="pepito";
        $obj->data=2000;
        $this->createTestIndex($obj);
        $ser=$this->getDefaultSerializer();
        $ser->add([$obj]);
        sleep(1); // Para que se refresque el indice de ES
        $this->assertEquals(1,$ser->count(null,$obj));
    }
    function testUnserialize()
    {
        $obj=$this->getSimpleDefinedObject();
        $obj->id=1;
        $obj->name="pepito";
        $obj->data=2000;
        $this->createTestIndex($obj);
        $ser=$this->getDefaultSerializer();
        $ser->add([$obj]);
        sleep(1);
        $obj2=$this->getSimpleDefinedObject();
        $ser->unserialize($obj2,["CONDITIONS"=>[["FILTER"=>["F"=>"id","OP"=>"=","V"=>1]]]]);
        $this->assertEquals(2000,$obj2->data);

    }
    function testUnserialize2()
    {
        $obj=$this->getSimpleDefinedObject();
        $obj->id=1;
        $obj->name="pepito";
        $obj->data=2000;
        $this->createTestIndex($obj);
        $ser=$this->getDefaultSerializer();
        $ser->add([$obj]);
        sleep(1);
        $obj2=$this->getSimpleDefinedObject();
        $obj2->id=1;
        $ser->unserialize($obj2);
        $this->assertEquals(2000,$obj2->data);

    }
    function testUpdate()
    {
        $obj=$this->getSimpleDefinedObject();
        $obj->id=1;
        $obj->name="pepito";
        $obj->data=2000;
        $obj->save();
        $this->createTestIndex($obj);

        $ser=$this->getDefaultSerializer();
        $ser->add([$obj]);
        $obj->data=2001;
        sleep(1);
        $ser->update($obj);
        sleep(1);
        $obj2=$this->getSimpleDefinedObject();
        $ser->unserialize($obj2,["CONDITIONS"=>[["FILTER"=>["F"=>"id","OP"=>"=","V"=>1]]]]);
        $this->assertEquals(2001,$obj2->data);
    }
    function testDelete()
    {
        $obj=$this->getSimpleDefinedObject();
        $obj->id=1;
        $obj->name="pepito";
        $obj->data=2000;
        $obj->save();
        $this->createTestIndex($obj);

        $ser=$this->getDefaultSerializer();
        $ser->add([$obj]);

        sleep(1);
        $obj2=$this->getSimpleDefinedObject();
        $obj2->id=1;
        $ser->delete($obj2);
        sleep(1);
        $obj3=$this->getSimpleDefinedObject();
        $obj3->id=1;
        $this->expectException('\lib\storage\ES\ESSerializerException');
        $this->expectExceptionCode(\lib\storage\ES\ESSerializerException::ERR_NO_SUCH_OBJECT);
        $ser->unserialize($obj3);
    }
}
