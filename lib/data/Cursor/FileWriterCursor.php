<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 0:12
 */

namespace lib\data\Cursor;


class FileWriterCursor extends Cursor
{
    var $op;
    var $isFirst=true;
    function init($params)
    {
        $this->op=fopen($params["fileName"],"w");
        $v=$this;
        $params["callback"]=function($rows) use ($v){

            for($k=0;$k<count($rows);$k++) {
                fputs($v->op, $rows[$k]);
            }
            return $rows;
        };

        $endCallback=isset($params["endCallback"])?$params["endCallback"]:null;
        $params["endCallback"]=function() use ($v,$endCallback){
            fclose($v->op);if($endCallback){call_user_func($endCallback);}
        };
        parent::init($params);
    }
}
