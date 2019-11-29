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
    var $metadata=null;
    var $state=0;


    function init($params)
    {
        parent::init($params);
        $this->callback=$params["callback"];
        $this->nRows=isset($params["nRows"])?$params["nRows"]:1;
        parent::init($params);
    }
    function setMetaData($metadata)
    {
        $this->metadata=$metadata;
    }
    function push($data)
    {
        if($this->state==0)
        {
            // Si este cursor tiene metadata, la "empuja" a sus hijos.
            if($this->metadata!==null) {
                for ($j = 0; $j < count($this->subCursors); $j++)
                    $this->subCursors[$j]->setMetaData($this->metadata);
            }
            $this->state=1;
        }
        $this->currentData[]=$data;
        if($this->nRows==1 || count($this->currentData) >= $this->nRows)
            $this->process();
    }
    function mpush($data)
    {
        if($this->state==0)
        {
            // Si este cursor tiene metadata, la "empuja" a sus hijos.
            if($this->metadata!==null) {
                for ($j = 0; $j < count($this->subCursors); $j++)
                    $this->subCursors[$j]->setMetaData($this->metadata);
            }
            $this->state=1;
        }
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
        $n=count($this->currentData);
        if($n==0)
            return;
        $newRows=[];
        for($k=0;$k<count($this->currentData);$k++)
            $newRows[] = call_user_func($this->callback,$this->currentData[$k],$this);
        if(!is_array($newRows))
        {
            throw new CursorException(CursorException::ERR_CALLBACK_RETURN_VALUE_MUST_BE_AN_ARRAY);
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
        // Si se habian quedado elementos sin procesar, antes de que el productor llegara al final, se procesan ahora.
        if(count($this->currentData)>0)
            $this->process();
        parent::end();
    }
}
