<?php
/**
 * Class FileReaderCursorTest
 * @package lib\tests\data\Cursor
 *  (c) Smartclip
 */


namespace lib\tests\data\Cursor;
use lib\data\Cursor\FileReaderCursor;
use lib\data\Cursor\Cursor;

include_once(__DIR__."/../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/autoloader.php");
include_once(LIBPATH."/data/Cursor/FileReaderCursor.php");

use PHPUnit\Framework\TestCase;
class FileReaderCursorTest extends TestCase
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
        $fr=new FileReaderCursor();

        // Se crea un cursor, $cr1, para convertir las lineas del fichero, en un array.
        $cr=new Cursor();
        $columns=null;
        $cr->init([
            "meta"=>$this->getTestArrayTypes(),
            "callback"=>function($item,$cursor) use (& $columns){
            if($columns==null) {
                $meta = $cursor->getMetaData();
                $columns = array_keys($meta);
            }
            $parts=explode(" ",$item);
            return array_combine($columns,$parts);
        }]);
        // Se añade el cursor a $fr
        $fr->addCursor($cr);

        // Se crea un segundo cursor, para sumar las lineas
        $cr2=new Cursor();
        $sum=0;
        $cr2->init(["callback"=>function($item,$cursor) use (&$sum){
            $sum+=$item["A"];
            return $item;
        }]);
        // Se añade al cursor de procesado.
        $cr->addCursor($cr2);
        // Se inicializa el cursor de fichero.
        $fr->init(["fileName"=>__DIR__."/res/sampleData.txt"]);
        // Comienza a producir
        while($fr->produce());
        // Miramos si la suma es 6.
        $this->assertEquals(6,$sum);
    }

    // Identico a test1, pero en ese caso, se reciben las filas de 2 en 2
    function test2()
    {
        // Se crea el cursor de lectura de fichero.
        $fr=new FileReaderCursor();
        // Se crea un cursor, $cr1, para convertir las lineas del fichero, en un array.
        $cr=new Cursor();
        $columns=null;
        $cr->init([
            "meta"=>$this->getTestArrayTypes(),
            "nRows"=>2,
            "callback"=>function($item,$cursor) use (& $columns){
                if($columns==null) {
                    $meta = $cursor->getMetaData();
                    $columns = array_keys($meta);
                }
                $parts=explode(" ",$item);
                return array_combine($columns,$parts);
            }]);
        // Se añade el cursor a $fr
        $fr->addCursor($cr);

        // Se crea un segundo cursor, para sumar las lineas
        $cr2=new Cursor();
        $sum=0;
        $cr2->init(["callback"=>function($item,$cursor) use (&$sum){
            $sum+=$item["A"];
            return $item;
        }]);
        // Se añade al cursor de procesado.
        $cr->addCursor($cr2);
        // Se inicializa el cursor de fichero.
        $fr->init(["fileName"=>__DIR__."/res/sampleData.txt"]);
        // Comienza a producir
        $nProduces=0;
        while($fr->produce()){$nProduces++;};
        // Miramos si la suma es 6.
        $this->assertEquals(6,$sum);
    }
}
