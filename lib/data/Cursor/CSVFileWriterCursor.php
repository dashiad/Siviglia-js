<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 0:12
 */

namespace lib\data\Cursor;


class CSVFileWriterCursor extends Cursor
{
    var $op;
    var $isFirst=true;
    function init($params)
    {
        $this->op=fopen($params["fileName"],"w");
        $v=$this;
        $params["callback"]=function($rows) use ($v){
            if ($this->isFirst) {
                fputcsv($v->op, array_keys($rows[0]));
                $v->isFirst = false;
            }
            for($k=0;$k<count($rows);$k++) {
                fputcsv($v->op, array_values($rows[$k]));
            }
            return $rows;
        };
        $params["nRows"]=1;
        $endCallback=isset($params["endCallback"])?$params["endCallback"]:null;
        $params["endCallback"]=function() use ($v,$endCallback){
            fclose($v->op);if($endCallback){call_user_func($endCallback);}
        };
        parent::init($params);
    }
}
