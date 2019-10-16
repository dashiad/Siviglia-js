<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 9:49
 */

namespace lib\data\Cursor;
include_once(__DIR__."/ArrayReaderCursor.php");

class CSVArrayReaderCursor extends ArrayReaderCursor
{
    var $headers;
    function init($params)
    {
        $this->headers=$params["headers"];
        parent::init($params);
    }
    function produce()
    {
        if($this->curIndex < $this->nRows)
            $this->arr[$this->curIndex]=array_combine($this->headers,$this->arr[$this->curIndex]);
        return parent::produce();
    }
}
