<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/10/2016
 * Time: 13:01
 */

namespace lib\data\Cursor;
include_once(__DIR__."/CursorFactory.php");

class ChainedReaderCursor extends ReaderCursor
{
    var $producer;
    var $lastCursor;
    function init($params)
    {
        $this->producer=$params["producer"];
        $this->lastCursor=$params["lastCursor"];
        parent::init($params);

    }
    function produce()
    {
        $producer=\lib\data\Cursor\CursorFactory::getCursor($this->producter);
        $producer->init($this->producer);
        return $this->producer->produce();
    }
    function addCursor($c)
    {
        $this->lastCursor->addCursor($c);
    }
    function end()
    {
        $this->producer->end();
        if($this->endCallback)
            call_user_func($this->endCallback);
    }
}
