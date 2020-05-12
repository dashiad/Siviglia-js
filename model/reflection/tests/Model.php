<?php

namespace model\reflection\tests;

include_once(__DIR__ . "/../../../install/config/CONFIG_test.php");
include_once(LIBPATH . "/startup.php");
include_once(LIBPATH . "/autoloader.php");
include_once(PROJECTPATH . "/vendor/autoload.php");

use PHPUnit\Framework\TestCase;

include_once(__DIR__ . "/stubs/model/tests/Package.php");

class Model extends TestCase
{
    var $initialized = false;

    function initialize()
    {
        if (!$this->initialized) {
            $this->initialized = true;
            \Registry::getService("model")->addPackage(new \model\tests\Package());
        }
    }
    function test1()
    {
        $this->initialize();
        $newModel=new \model\reflection\Model('\model\tests\User');
        $newModel->loadFromFields();
        $h=11;
    }
/*    function testLoad()
    {
        $this->initialize();
        $user = new \model\reflection\Model('\model\tests\User');
        $user->initialize();
        $user->initializeAliases();


        $def = $user->getDefinition();
        $this->assertEquals('ENTITY', $def['ROLE']);
        $this->assertEquals('web', $def['DEFAULT_SERIALIZER']);
        $this->assertEquals('web', $def['DEFAULT_WRITE_SERIALIZER']);
        $this->assertEquals('id', $def['INDEXFIELDS'][0]);
        $this->assertEquals('User', $def['TABLE']);
        $this->assertEquals('User', $def['LABEL']);
        $this->assertEquals('User', $def['SHORTLABEL']);
        $this->assertEquals('3000', $def['CARDINALITY']);
        $this->assertEquals('FIXED', $def['CARDINALITY_TYPE']);
        $this->assertEquals('AutoIncrement', $def["FIELDS"]["id"]['TYPE']);
        $this->assertEquals('User Id', $def["FIELDS"]["id"]['LABEL']);

        $this->assertEquals('String', $def["FIELDS"]["Name"]['TYPE']);
        $this->assertEquals('Name', $def["FIELDS"]["Name"]['LABEL']);
        $this->assertEquals(40, $def["FIELDS"]["Name"]['MAXLENGTH']);
        $this->assertEquals(false, $def["FIELDS"]["Name"]['TRIM']);
        $this->assertEquals(false, $def["FIELDS"]["Name"]['NORMALIZE']);
        $this->assertEquals(false, $def["FIELDS"]["Name"]['REQUIRED']);



        $this->assertEquals('InverseRelation', $def["ALIASES"]["posts"]['TYPE']);
        $this->assertEquals('\\model\\tests\\Post', $def["ALIASES"]["posts"]['MODEL']);
        $this->assertEquals('HAS_MANY', $def["ALIASES"]["posts"]['ROLE']);
        $this->assertEquals('1:N', $def["ALIASES"]["posts"]['MULTIPLICITY']);
        $this->assertEquals(100, $def["ALIASES"]["posts"]['CARDINALITY']);
        $k = array_keys($def["ALIASES"]["posts"]["FIELDS"]);
        $this->assertEquals("id", $k[0]);
        $this->assertEquals("creator_id", $def["ALIASES"]["posts"]["FIELDS"][$k[0]]);

        $this->assertEquals('InverseRelation', $def["ALIASES"]["comments"]['TYPE']);
        $this->assertEquals('\\model\\tests\\Post\\Comment', $def["ALIASES"]["comments"]['MODEL']);
        $this->assertEquals('HAS_MANY', $def["ALIASES"]["comments"]['ROLE']);
        $this->assertEquals('1:N', $def["ALIASES"]["comments"]['MULTIPLICITY']);
        $this->assertEquals(100, $def["ALIASES"]["comments"]['CARDINALITY']);
        $k = array_keys($def["ALIASES"]["comments"]["FIELDS"]);
        $this->assertEquals("id", $k[0]);
        $this->assertEquals("id_user", $def["ALIASES"]["comments"]["FIELDS"][$k[0]]);


        $this->assertEquals('RelationMxN', $def["ALIASES"]["roles"]['TYPE']);
        $this->assertEquals('\\model\\tests\\User\\UserRole', $def["ALIASES"]["roles"]['MODEL']);
        $this->assertEquals('\\model\\tests\\User\\Roles', $def["ALIASES"]["roles"]['REMOTE_MODEL']);
        $k = array_keys($def["ALIASES"]["roles"]["FIELDS"]);
        $this->assertEquals("id", $k[0]);
        $this->assertEquals("id_user", $def["ALIASES"]["roles"]["FIELDS"][$k[0]]);
        $this->assertEquals('HAS_MANY', $def["ALIASES"]["roles"]['ROLE']);
        $this->assertEquals('M:N', $def["ALIASES"]["roles"]['MULTIPLICTY']);
        $this->assertEquals(100, $def["ALIASES"]["roles"]['CARDINALITY']);

    }
    function testLoad2()
    {
        $this->initialize();
        $userrole = new \model\reflection\Model('\model\tests\User\UserRole');
        $userrole->initialize();
        $userrole->initializeAliases();
        $def=$userrole->getDefinition();
        $h=11;

    }
    function testLoad3()
    {
        $this->initialize();
        $role = new \model\reflection\Model('\model\tests\User\Role');
        $role->initialize();
        $role->initializeAliases();
    }
*/
}
