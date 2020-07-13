<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 0:30
 */

namespace lib\data\Cursor;
include_once(__DIR__."/BaseCursor.php");


abstract class ReaderCursor extends BaseCursor
{

    function process()
    {
        while($this->produce());
        $this->end();
    }
    abstract function produce();
    function push($row)
    {
        for($j=0;$j<count($this->subCursors);$j++) {
            $this->subCursors[$j]->push($row);
        }
    }
    function mpush($data)
    {
        for($j=0;$j<count($this->subCursors);$j++) {
            $this->subCursors[$j]->mpush($data);
        }
    }
    function end()
    {
        for($j=0;$j<count($this->subCursors);$j++)
            $this->subCursors[$j]->end();
        if($this->endCallback)
            call_user_func($this->endCallback);
    }
}
