<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/10/2016
 * Time: 12:37
 */

namespace lib\data\Cursor;


class FileReaderCursor  extends ReaderCursor
{
    var $op;
    function init($params)
    {
        $fileName=$params["fileName"];
        parent::init($params);
        $this->op = fopen($fileName, 'r');
    }
    function produce()
    {
        $buffer = fgets($this->op, 1000000);
        if(!$buffer) {
            fclose($this->op);
            return false;
        }
        $this->push($buffer);
        return true;
    }
}
