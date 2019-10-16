<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 27/09/2016
 * Time: 15:49
 */

namespace lib\data\Cursor;
include_once(__DIR__."/BaseCursor.php");

class Cursor extends BaseCursor
{
    var $rows=array();
    var $nRows=0;
    var $callback;
    var $currentData;


    function init($params)
    {
        parent::init($params);
        $this->callback=$params["callback"];

        $this->nRows=isset($params["nRows"])?$params["nRows"]:1;
        if(isset($params["endCallback"]))
            $this->setEndCallback($params["endCallback"]);
    }
    function push($data)
    {
        $this->currentData[]=$data;
        if($this->nRows==1 || count($this->currentData) >= $this->nRows)
            $this->process();
    }
    function mpush($data)
    {

        $this->currentData=array_merge($this->currentData?$this->currentData:[],$data);
        if($this->nRows==1 || count($this->currentData) >= $this->nRows)
            $this->process();
    }
    function setData($data)
    {
        for($k=0;$k<count($data);$k++)
            $this->push($data[$k]);
        $this->end();
    }
    function process()
    {
        if(!is_array($this->currentData))
        {
            $r=11;
        }
        $n=count($this->currentData);
        if($n==0)
            return;
        $newRows = call_user_func($this->callback,$this->currentData);
        if(!is_array($newRows))
        {
            $ss=11;
            echo "CURSOR: CALLBACK RETURN VALUE SHOULD BE AN ARRAY, GOT:<br>";
            var_dump($newRows);
            die();
        }
        for($k=0;$k<count($newRows);$k++)
        {
            for($j=0;$j<count($this->subCursors);$j++)
                $this->subCursors[$j]->push($newRows[$k]);
        }
        $this->currentData=[];
    }
    function end()
    {
        parent::end();
    }
}
