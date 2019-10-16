<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 9:42
 */

namespace lib\data\Cursor;


class ArrayReaderCursor extends ReaderCursor
{
    var $arr;
    var $curIndex;
    var $nRows=0;
    function init($params)
    {
        $this->arr=$params["ARRAY"];
        $this->nRows=count($this->arr);
        $this->curIndex=0;
        parent::init($params);
    }
    function produce()
    {
        if($this->curIndex >= $this->nRows)
            return false;
        $this->push($this->arr[$this->curIndex]);
        $this->curIndex++;
        return true;
    }
}
