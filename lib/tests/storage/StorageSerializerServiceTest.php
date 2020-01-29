<?php
/**
 * Class StorageSerializerServiceTest
 *  (c) Smartclip
 */
namespace lib\tests\storage\ES;
include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use lib\model\BaseTypedObject;


class StorageSerializerServiceTest extends TestCase
{
     function getDefaultService()
     {
         global $Config;
         $s=new \lib\Storage\StorageSerializerService();
         $sc=$Config["SERIALIZERS"]["default"];
         $def=[
             "default"=>"default",
             "serializers"=>[
             ]
         ];
         $def["serializers"]["default"]=$sc;
         $def["serializers"]["backoffice"]=$Config["SERIALIZERS"]["backoffice"];
         $s->init($def);
         return $s;
     }
     function testCreateService()
     {
         $s=$this->getDefaultService();
         $ss=$s->getDefaultSerializer();
         $this->assertEquals("default",$ss->getName());
     }
     function testAddSerializer()
     {
         $s=$this->getDefaultService();
         global $Config;
         $details=$Config["SERIALIZERS"]["test"]["ADDRESS"];
         $s->addSerializer("added",
                            array(
                                    "NAME"=>"added",
                                    "TYPE"=>"Mysql",
                                    "ADDRESS"=>$details
                            )
         );
         $ss=$s->getSerializerByName("added");
         $this->assertEquals("added",$ss->getName());
     }
     function testNonExistentSerializer()
     {
         $this->expectException("\\lib\\storage\\StorageSerializerServiceException");
         $s=$this->getDefaultService();
         $s->getSerializerByName("NONEXISTENT");
     }
     function testSerializeType()
     {
         $str=new \lib\model\types\_String(["TYPE"=>"String"]);
         $str->setValue('aa"bb');
         $s=$this->getDefaultService();
         $serializer=$s->getTypeSerializer($str,"default");
         $className=get_class($serializer);
         $this->assertEquals('lib\storage\Mysql\types\_String',$className);
     }
     function testSerializeDerivedType()
     {
         $str=new \lib\model\types\_String(["TYPE"=>"NIF"]);
         $str->setValue('aa"bb');
         $s=$this->getDefaultService();
         $serializer=$s->getTypeSerializer($str,"default");
         $className=get_class($serializer);
         $this->assertEquals('lib\storage\Mysql\types\_String',$className);
     }
}
