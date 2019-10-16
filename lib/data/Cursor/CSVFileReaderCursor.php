<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 0:12
 */

namespace lib\data\Cursor;
include_once(__DIR__."/ReaderCursor.php");
include_once(__DIR__."/CursorException.php");

class CSVFileReaderCursor extends ReaderCursor
{
    var $op;
    var $isFirst=true;
    function init($params)
    {
        if (!is_file($params["fileName"]))
            throw new CursorException(CursorException::SOURCE_DOESNT_EXIST);
        $this->params=$params;
        $this->op=fopen($this->params["fileName"],"r");
        $this->headers=null;
        $endCallback=isset($params["endCallback"])?$params["endCallback"]:null;
        $v=$this;
        $params["endCallback"]=function() use ($v,$endCallback){
            fclose($v->op);if($endCallback){call_user_func($endCallback);}
        };
        $params["nRows"]=1;
        parent::init($params);
    }
    function produce()
    {
        if ($this->isFirst) {
            $this->headers=fgetcsv($this->op);
            $this->isFirst = false;
        }

        $r=fgetcsv($this->op);
        if(!$r)
            return false;
        $this->push(array_combine($this->headers,$r));
        return true;

    }
}
