<?php
/**
 * Class CSVFileReaderCursorTest
 * @package lib\tests\data\Cursor
 *  (c) Smartclip
 */


namespace lib\tests\data\Cursor;
use lib\data\Cursor\CSVFileReaderCursor;
use lib\data\Cursor\Cursor;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/autoloader.php");
include_once(LIBPATH."/data/Cursor/CSVFileReaderCursor.php");

use PHPUnit\Framework\TestCase;
class CSVFileReaderCursorTest extends TestCase
{
    function getTestArrayTypes()
    {
        return [
            "A"=>["TYPE"=>"Integer"],
            "B"=>["TYPE"=>"Integer"],
            "C"=>["TYPE"=>"String"],
        ];
    }
    function test1()
    {
        // Se crea el cursor de lectura de fichero.
        $fr=new CSVFileReaderCursor();

        // Se crea un cursor, $cr1, para convertir las lineas del fichero, en un array.
        $cr=new Cursor();
        $columns=null;
        $sum=0;
        $cr->init([
            "callback"=>function($item,$cursor) use (& $columns,& $sum){
              $p=$item;
              $sum+=$item["Hour"];
              return $item;
        }]);
        // Se añade el cursor a $fr
        $fr->addCursor($cr);

        // Se crea un segundo cursor, para sumar las lineas

        $fr->init(["fileName"=>__DIR__."/res/simple.csv"]);
        // Comienza a producir
        while($fr->produce());
        // Miramos si la suma es 6.
        $this->assertEquals(78,$sum);
    }

    // Se comprueba el mapeo de cabeceras.Hay 1 cabecera que no se mapea.Se prueba que esa cabecera no cambia.
    function test2()
    {
        // Se crea el cursor de lectura de fichero.
        $fr=new CSVFileReaderCursor();

        // Se crea un cursor, $cr1, para convertir las lineas del fichero, en un array.
        $cr=new Cursor();
        $columns=null;
        $sum=0;
        $nSet=0;
        $cr->init([
            "callback"=>function($item,$cursor) use (& $columns,& $sum,& $nSet){
                $p=$item;
                $sum+=$item["Hour"];
                $nSet+=isset($item["Ad Exchange impressions"])?1:0;
                return $item;
            }]);
        // Se añade el cursor a $fr
        $fr->addCursor($cr);

        // Se crea un segundo cursor, para sumar las lineas

        $fr->init(["fileName"=>__DIR__."/res/simple.csv",
            "fieldMap"=>[
                "MODEL"=>"",
                "SERIALIZER"=>"",
                "FIELDS"=>[
                    "Date"=>"DATE",
                    "Ad unit"=>"AD_UNIT",
                    "Hour"=>"HOUR",
                    "Ad unit ID"=>"AD_UNIT_ID",
                    "Total impressions"=>"IMPTOTAL",
                    "Total CPM and CPC revenue (€)"=>"CMPTOTAL" ,
                    "Total CTR"=>"TOTALCTR",
                    //"Ad Exchange impressions"=>"IMPRESSIONS",
                    "Ad Exchange revenue (€)"=>"ADEX_REVENUE"
                ]
        ]]);
        // Comienza a producir
        while($fr->produce());
        // Miramos si la suma es 6.
        $this->assertEquals(78,$sum);
        $this->assertEquals(13,$nSet);
    }


}
