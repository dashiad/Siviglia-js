<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 18/01/2018
 * Time: 3:31
 */

namespace lib\tests\model\permissions;
$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/permissions/PermissionsManager.php");

use PHPUnit\Framework\TestCase;
use lib\model\permissions\AclManager;


class PermissionsManager
{


}