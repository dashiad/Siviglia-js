<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/10/2016
 * Time: 12:37
 */

namespace lib\data\Cursor;


class CompressedFileCursor  extends ReaderCursor
{
    var $op;
    function init($params)
    {
        $this->op = gzopen($params["fileName"], 'r');
        parent::init($params);
    }
    function produce()
    {
        $buffer = gzgets($this->op, 1000000);
        if(!$buffer) {
            gzclose($this->op);
            return false;
        }
        $this->push($buffer);
        return true;
    }
}
