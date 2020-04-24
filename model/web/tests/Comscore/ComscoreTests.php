<?php
/**
 * Class Package
 * @package model\reflection\tests
 *  (c) Smartclip
 */


namespace model\web\tests;

include_once(__DIR__."/../../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/vendor/autoload.php");


use PHPUnit\Framework\TestCase;

class ComscoreTests extends TestCase
{

    function testStorageEngine()
    {
        $s=\Registry::getService("storage");
        $ser=$s->getSerializerByName("comscore");
        $this->assertEquals(true, is_a($ser,'\model\web\Comscore\serializers\ComscoreSerializer'));
    }
    function testDataSource()
    {
        $d=\lib\datasource\DataSourceFactory::getDataSource("/model/web/Comscore","DemographicReport");
        $d->region="spain";
        $d->start_date=\lib\model\types\DateTime::getValueFromTimestamp(time()-30*24*60*60);
        $d->end_date=\lib\model\types\DateTime::getValueFromTimestamp(time());
        $it=$d->fetchAll();
        $n=$it->count();
    }
}

?>