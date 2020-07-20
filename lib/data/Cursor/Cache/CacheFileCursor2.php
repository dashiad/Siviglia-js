<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 11:00
 */

namespace lib\data\Cursor;

include_once(__DIR__ . "/../../../Util/TimeRanges.php");
include_once(__DIR__ . "../CursorFactory.php");
include_once(__DIR__ . "../ReaderCursor.php");
include_once(__DIR__."Cache.php");

use lib\data\Cursor\Cache\Cache;
/*
 *  PARAMETROS:
 *  directory
 * prefix
 * suffix
 * timeSpec
 * subCursor --> Especificacion del cursor a crear si no existe la cache.
 * fileCursor --> Especificcion del cursor de fichero a crear.
 *
 */

class CacheFileCursor extends Cache
{
    var $prefix;
    var $directory;
    var $timeSpec;
    var $subCursor;
    var $fileCursor;
    function init($params)
    {
        $this->directory=$params["directory"];
        $this->prefix=$params["prefix"];
        $this->suffix=$params["suffix"];
        $this->timeSpec=$params["timeSpec"];
        $this->subCursor=$params["subCursor"];
        $this->readCursor=$params["readCursor"];
        $this->writeCursor=$params["writeCursor"];

    }
    function checkDirectory()
    {
        if(!is_dir($this->directory))
        {
            if(!@mkdir($this->directory,0777,true))
                throw new CacheFileCursorException(FileCacheControllerException::ERR_CANT_CREATE_DIRECTORY);
        }
    }
    function getFileName($timeSpec)
    {
        $ds=\lib\Util\TimeRanges::getDateString($timeSpec);
        return $this->prefix."-".$ds.".".$this->suffix;
    }
    function getFilePath($timeSpec)
    {
        return $this->directory.DIRECTORY_SEPARATOR.$this->getFileName($timeSpec);
    }
    function checkFile()
    {
        $this->checkDirectory();
        return is_file($this->getFilePath($this->timeSpec));
    }
    function produce()
    {
        if($this->checkFile())
        {

            $this->readCursor["fileName"]=$this->getFilePath($this->timeSpec);
            $fileCursor=\lib\data\Cursor\CursorFactory::getCursor($this->readCursor);
        }
        else
        {
            echo "FICHERO CACHE NO ENCONTRADO\n";
            $cursor=\lib\data\Cursor\CursorFactory::getCursor($this->subCursor);
            $this->writeCursor["fileName"]=$this->getFilePath($this->timeSpec);
            $writerCursor=\lib\data\Cursor\CursorFactory::getCursor($this->writeCursor);
            $this->subCursor["endCallback"]=function() use (& $writerCursor){ $writerCursor->end();};

            $cursor->init($this->subCursor);
            $cursor->addCursor($writerCursor);
            $cursor->addCursor($this);
            $cursor->process();
        }
        return false;
    }
}
