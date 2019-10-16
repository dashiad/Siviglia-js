<?php


namespace lib\data\Cursor\ES;
include_once(LIBPATH."/data/Cursor/Cursor.php");
include_once(LIBPATH . "storage/ES/ESClient.php");
class ESWriterCursor extends \lib\data\Cursor\Cursor
{
    function init($params)
    {
        $this->client=new \lib\ES\ESClient($params["hosts"]);
        $this->indexName=$params["indexName"];
        $this->docType=$params["docType"];
        $me=$this;
        $currentData=[];
        $params["callback"]=function($item)use (& $currentData,$me){
            $currentData=array_merge($currentData,$item);
            if(count($currentData)>1000) {
                $me->client->insertBulk($me->indexName, $me->docType, $currentData);
                $currentData=[];
            }
            return $item;
        };
        $params["endCallback"]=function() use (& $currentData,$me){
            if(count($currentData)>0) {
                $me->client->insertBulk($me->indexName, $me->docType, $currentData);
            }
        };
        parent::init($params);
    }
}
