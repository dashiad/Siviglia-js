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
    var $metaData;
    var $endCalled=false;

    function init($params)
    {
        $this->endCallback=isset($params["endCallback"])?$params["endCallback"]:null;
        if(isset($params["meta"]))
            $this->metaData=$params["meta"];
        $this->params=$params;
    }
    function getMetaData()
    {
        return $this->metaData;
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
        if($this->endCalled)
            return;
        $this->endCalled=true;
        if($this->endCallback)
            call_user_func($this->endCallback,$this);

        for($j=0;$j<count($this->subCursors);$j++)
            $this->subCursors[$j]->end();
    }
    function mapColumnNames($r)
    {
        if(isset($this->params["headersMap"]))
        {
            $newCols=[];
            $map=$this->params["headersMap"];
            foreach($r as $k=>$v)
            {
                $oH=isset($map[$k])?$map[$k]:$k;
                $newCols[$oH]=$v;
            }
            return $newCols;
        }
        return $r;
    }
}
