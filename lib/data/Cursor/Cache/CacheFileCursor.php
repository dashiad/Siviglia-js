<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 11:00
 */

namespace lib\data\Cursor;

include_once(__DIR__ . "/../../../Util/TimeRanges.php");
include_once(__DIR__."../CursorException.php");
include_once(__DIR__ . "../CursorFactory.php");
include_once(__DIR__ . "../ReaderCursor.php");
include_once(__DIR__."Cache.php");
include_once(__DIR__."../../../model/BaseException.php");

use lib\data\Cursor\Cache\Cache;
use lib\model\BaseException;

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
class CacheFileCursorException extends BaseException
{
    const CANT_CREATE_DIRECTORY=1;
}
class CacheFileCursor extends Cache
{
    var $prefix;
    var $directory;
    var $timeSpec;
    var $subCursor;
    var $fileCursor;
    function init($params)
    {
        $this ->directory=$params["directory"];
        $this->prefix=$params["prefix"];
        $this->suffix=$params["suffix"];
        parent::init($params);
    }
    function getLastModifiedTime()
    {
        if(!$this->checkFile($this->timeSpec))
            return null;
    }

    function checkDirectory()
    {
        if(!is_dir($this->directory))
        {
            if(!@mkdir($this->directory,0777,true))
                throw new CacheFileCursorException(CacheFileCursorException::ERR_CANT_CREATE_DIRECTORY);
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
    function getReaderCursor()
    {
        $readerCursor=$this->params["readerCursor"];
        $readerCursor["fileName"] = $this->getFilePath($this->timeSpec);
        return \lib\data\Cursor\CursorFactory::getCursor($readerCursor);
    }
    function getWriterCursor()
    {
        $wCursor=$this->params["writerCursor"];
        $wCursor["fileName"]=$this->getFilePath($this->timeSpec);
        return \lib\data\Cursor\CursorFactory::getCursor($wCursor);
    }
    function reset()
    {
        $path=$this->getFilePath($this->timeSpec);
        if(is_file($path))
            unlink($path);
    }
}
