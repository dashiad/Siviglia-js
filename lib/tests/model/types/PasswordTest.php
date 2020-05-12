<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Password.php");


use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{

    function testDefinition1()
    {
        $ins=new \lib\model\types\Password("",[
            "TYPE"=>"Password"
        ]);
        $this->expectException('\lib\model\types\_StringException');
        $this->expectExceptionCode(\lib\model\types\_StringException::ERR_TOO_SHORT);
        $ins->setValue("hola");
    }
    function testDefinition2()
    {
        $ins=new \lib\model\types\Password("",[
            "TYPE"=>"Password",
            "COST"=>10
        ]);
    // $2y$10
        $ins->setValue("holahola");
        $ins->encode();
        $v=$ins->getValue();
        $this->assertEquals('$2y$10',substr($v,0,6));

    }
    function testDefinition3()
    {
        $ins=new \lib\model\types\Password("",[
            "TYPE"=>"Password",
            "PASSWORD_ENCODING"=>"BCRYPT",
            "COST"=>12
        ]);

        $ins->setValue("holahola");
        $ins->encode();
        $v=$ins->getValue();
        $this->assertEquals('$2y$12',substr($v,0,6));
    }
    function testDefinition4()
    {
        $ins=new \lib\model\types\Password("",[
            "TYPE"=>"Password",
            "PASSWORD_ENCODING"=>"ARGON2I",
            "COST"=>12
        ]);

        $ins->setValue("holahola");
        $ins->encode();
        $v=$ins->getValue();
        $this->assertEquals('$argon2i$',substr($v,0,9));
    }
    function testDefinition5()
    {
        $ins=new \lib\model\types\Password("",[
            "TYPE"=>"Password",
            "PASSWORD_ENCODING"=>"ARGON2I",
            "COST"=>12
        ]);

        $ins->setValue('$argon2i$v=19$m=65536,t=4,p=1$ZU5ENmpueDdaQkpzVGNFaA$jJP0+gHS5YYDpEJ9//M5VKgtDiWJywxwQrfOeH582qM');
        $this->assertEquals(false,$ins->check("adiosadios"));
        $this->assertEquals(true,$ins->check("holahola"));
    }

}
