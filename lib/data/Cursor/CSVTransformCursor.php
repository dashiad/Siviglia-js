<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/09/2016
 * Time: 0:24
 */

namespace lib\data\Cursor;
include_once(__DIR__."/Cursor.php");

class CSVTransformCursor extends Cursor
{
    var $op;
    var $isFirst=0;
    var $keys;
    function init($params)
    {
        $me=$this;
        $this->headers=null;
        $this->processHeadersCallback=isset($params["processHeadersCallback"])?$params["processHeadersCallback"]:null;
        $params["callback"]=function($line) use ($me) {
            if ($me->headers == null) {
                $this->headers = str_getcsv($line[0]);
                if ($me->processHeadersCallback) {
                    $me->headers = call_user_func($me->processHeadersCallback, $me->headers);
                }
                return array();
            }
            return array(array_combine($me->headers, str_getcsv($line[0])));
        };

        parent::init($params);
    }
}
