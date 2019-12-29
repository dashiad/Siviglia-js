<?php
/**
 * Class TransformCursor
 * @package lib\tests\data\Cursor
 *  (c) Smartclip
 */


namespace lib\tests\data\Cursor;


use lib\data\Cursor\CSVFileReaderCursor;
use lib\data\Cursor\TransformCursor;
use lib\data\Cursor\Cursor;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/autoloader.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/data/Cursor/StorageWriterCursor.php");

use model\ads\objects\AdManager\serializers\AdManagerCSVTypeSerializer;
use PHPUnit\Framework\TestCase;
class StorageWriterCursor extends TestCase
{
    var $serializer=null;
    function buildCustomSerializer()
    {
        if($this->serializer===null) {

            $package=new \lib\tests\data\res\model\Ads\Package();
            $modelService=\Registry::getService("model");
            $modelService->addPackage($package);

            $serializerService=\Registry::getService("storage");
            $serializerService->addSerializer("ADSManager-CSV",
                [
                    "MODEL"=>"/model/Ads/objects/AdManager",
                    "CLASS"=>"/CSV/AdManagerCSVTypeSerializer",
                    "PARAMS"=>[]
                ]
                );
            global $Config;
            $esDefinition=$Config["SERIALIZERS"]["es"];
            $serializerService->addSerializer("MAIN_ES",$esDefinition);
            // Obtenemos el serializer para destruir el indice destino si es necesario.
            $es=$serializerService->getSerializerByName("MAIN_ES");
            try {
                $es->destroyStorage(null, ES_TEST_INDEX);
            }catch(\Exception $e){}
        }
        // Se da de alta el serializador a ElasticSearch

        return $this->serializer;
    }


    function test1()
    {
        $this->buildCustomSerializer();

        $fr=new \lib\data\Cursor\CSVFileReaderCursor();
        $tC=new \lib\data\Cursor\TransformCursor();
        $cr=new \lib\data\Cursor\StorageWriterCursor();



        $fr->init(["fileName"=>__DIR__."/res/simple.csv"]);
        $tC->init([
            "typeMap"=>[
                "model"=>"/model/Ads/objects/AdManager",
                "serializer"=>"ADSManager-CSV"
            ]
        ]);
        global $Config;
        $testIndex=$Config["SERIALIZERS"]["es"]["ES"]["index"];
        $cr->init(
            [
                "serializer"=>"MAIN_ES",
                "batchSize"=>10,
                "target"=>$testIndex
            ]
        );

        $fr->addCursor($tC);
        $tC->addCursor($cr);



        // Comienza a producir
        while($fr->produce());

        $serializerService=\Registry::getService("storage");
        $es=$serializerService->getSerializerByName("MAIN_ES");
        $p=null;
        sleep(1);
        $n=$es->count(null,$p,ES_TEST_INDEX);

        $this->assertEquals(13,$n);



        // Se deserializa de ES una instancia
        $modelService=\Registry::getService("model");
        $sampleModel=$modelService->getModel("/model/Ads/objects/AdManager");
        $es->unserialize($sampleModel, [
            "CONDITIONS"=>[
                ["FILTER"=>["F"=>"HOUR","OP"=>"=","V"=>0]]
            ]
        ], null,ES_TEST_INDEX);
        $this->assertEquals("2019-11-11",$sampleModel->DATE);
        $this->assertEquals(0           ,$sampleModel->HOUR);
        $this->assertEquals("25138238"  ,$sampleModel->AD_UNIT_ID);
        $this->assertEquals(25719       ,$sampleModel->AD_SERVER_IMPRESSIONS);
        $this->assertEquals("ES000085"  ,$sampleModel->IO);


    }
}
