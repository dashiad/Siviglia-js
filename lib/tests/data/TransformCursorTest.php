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
include_once(LIBPATH."/data/Cursor/TransformCursor.php");

use model\ads\objects\AdManager\serializers\AdManagerCSVTypeSerializer;
use PHPUnit\Framework\TestCase;
class TransformCursorTest extends TestCase
{
    var $serializer=null;
    function buildCustomSerializer()
    {
        if($this->serializer===null) {
            $package=new \lib\tests\data\res\model\Ads\Package();
            $modelService=\Registry::getService("model");
            $modelService->addPackage("test",$package);
            $serializerService=\Registry::getService("storage");
            $serializerService->addSerializer("ADSManager-CSV",
                [
                    "MODEL"=>"/model/Ads/objects/AdManager",
                    "CLASS"=>"/CSV/AdManagerCSVTypeSerializer",
                    "PARAMS"=>[]
                ]
                );


        }
        return $this->serializer;
    }

    function test1()
    {
        $ser=$this->buildCustomSerializer();
        $tC=new \lib\data\Cursor\TransformCursor();
        $tC->init([
            "typeMap"=>[
                "model"=>"/model/Ads/objects/AdManager",
                "serializer"=>"ADSManager-CSV"
            ]
        ]);
        // Ahora creamos un cursor para leer de fichero.
        $fr=new CSVFileReaderCursor();
        // Se crea un cursor, $cr1, para almacenar lo transformado.
        $cr=new Cursor();
        $parsed=array();
        $cr->init([
            "callback"=>function($item,$cursor) use (& $parsed){
                $parsed[]=$item;
                return $item;
            }]);
        // Este cursor se aniade al transform cursor
        $tC->addCursor($cr);
        // Y el transforCursor, al fichero.
        $fr->addCursor($tC);
        $fr->init(["fileName"=>__DIR__."/res/simple.csv"]);
        // Comienza a producir
        while($fr->produce());
        // Se comprueba que los campos han sido deserializados correctamente
        $this->assertEquals(13,count($parsed));

        $it=$parsed[0];
        // Se comprueba que estan todos los campos que esperamos:
        $this->assertEquals(10,count(array_keys($it)));
        // Deserializacion de fechas OK
        $this->assertEquals("2019-11-11",$it["DATE"]);
        // Deserializacion de campo compuesto OK
        $this->assertEquals("HOGARUTIL.ES",$it["AD_UNIT_NAME"]);
        // Creacion de campo nuevo OK
        $this->assertEquals("ES000085",$it["IO"]);
        // Deserializacion de campo Integer ok:
        $this->assertEquals(3629,$it["AD_EXCHANGE_IMPRESSIONS"]);
        // Deserializacion de campo percentage ok:
        $this->assertEquals(0.48,$it["TOTAL_LINE_ITEM_LEVEL_CTR"]);
        // Deserializacion de campo Decimal ok
        $this->assertEquals("33.18",$it["AD_SERVER_CPM_AND_CPC_REVENUE"]);
    }
}
