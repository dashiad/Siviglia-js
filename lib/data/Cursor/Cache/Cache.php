<?php


namespace lib\data\Cursor\Cache;


abstract class Cache
{
    var $maxLife;
    var $params;
    function init($params)
    {
        $this->maxLife=$params->maxLife;
        $this->params=$params;
    }
    function isUpToDate()
    {
        $lastModified=$this->getLastModifiedTime();
        if($lastModified==null)
            return false;
        return $lastModified+$this->maxLife > time();
    }
    abstract function getReaderCursor();
    abstract function getWriterCursor();
    abstract function reset();
    abstract function getLastModifiedTime();

}
