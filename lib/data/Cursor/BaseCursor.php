<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 27/09/2016
 * Time: 15:49
 */

namespace lib\data\Cursor;


class BaseCursor
{
    var $subCursors=array();
    var $endCallback;
    var $params;
    function init($params)
    {
        $this->endCallback=isset($params["END_CALLBACK"])?$params["END_CALLBACK"]:null;
        $this->params=$params;
    }
    function addCursor($c)
    {
        $this->subCursors[]=$c;
    }
    function setEndCallback($cbk)
    {
        $this->endCallback=$cbk;
    }
    function end()
    {
        if($this->endCallback)
            call_user_func($this->endCallback);

        for($j=0;$j<count($this->subCursors);$j++)
            $this->subCursors[$j]->end();
    }

}
