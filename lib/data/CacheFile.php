<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 27/09/2016
 * Time: 22:23
 */

namespace lib\data;


class CacheFile
{
    static function exists($api,$query,$start,$end)
    {
        $file=CacheFile::getCacheFileName($api,$query,$start,$end);
        return is_file($file);
    }
    static function getCacheWriteCursor($api,$query,$start,$end)
    {
        CacheFile::createDirectory($api);
        $file=CacheFile::getCacheFileName($api,$query,$start,$end);
        return new \lib\data\Cursor\CSVFileWriterCursor($file);
    }
    static function getCacheReadCursor($api,$query,$start,$end)
    {
        CacheFile::createDirectory($api);
        $file=CacheFile::getCacheFileName($api,$query,$start,$end);
        $fileCursor=new \lib\data\Cursor\FileCursor($file);
        $reader=new \lib\data\Cursor\CSVTransformCursor();
        $fileCursor->addCursor($reader);
        return new \lib\data\Cursor\ChainedReaderCursor($fileCursor,$reader);
    }

    static function createDirectory($api)
    {
        $apiDir=CacheFile::getAPIDirectory($api);
        if(!is_dir($apiDir))
            mkdir($apiDir,0777,true);
    }
    static function getAPIDirectory($api)
    {
        return TMP_DIRECTORY."/".$api."/reporting";
    }
    static function getCacheFileName($api,$query,$start,$end)
    {
        return CacheFile::getAPIDirectory($api)."/".$query."-".str_replace(":","-",$start)."--".str_replace(":","-",$end).".srl";
    }
}
