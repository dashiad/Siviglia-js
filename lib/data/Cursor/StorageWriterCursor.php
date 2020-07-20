<?php
/**
 * Class StorageWriterCursor
 * @package lib\data\Cursor
 *  (c) Smartclip
 */


namespace lib\data\Cursor;
include_once(LIBPATH."/data/Cursor/Cursor.php");


class StorageWriterCursor extends \lib\data\Cursor\Cursor
{
    var $storage=null;
    var $storageChecked=false;
    var $batch=[];
    var $nItems=0;
    var $batchSize=1;
    function init($params)
    {
        $service=\Registry::getService("storage");
        $this->storage=$service->getSerializerByName($params["serializer"]);
        $me=$this;
        $params["callback"]=function($item) use ($me)
        {
            $me->processLine($item);
        };
        $params["endCallback"]=function() use ($me)
        {
            $me->flush();
        };
        if(isset($params["batchSize"]))
        {
            $this->batchSize=$params["batchSize"];
        }
        parent::init($params);

    }
    function processLine($data)
    {
        if($this->storageChecked==false)
        {
            $this->checkStorage($data);
        }
        $this->batch[]=$data;
        $this->nItems++;
        if($this->nItems==$this->batchSize) {
            $this->flush();
        }
    }
    function flush()
    {
        if($this->nItems>0)
            $this->storage->insertFromAssociative($this->params["target"],$this->batch);
        $this->batch = [];
        $this->nItems = 0;
    }

    function checkStorage($data)
    {
        if($this->metadata!==null && isset($this->metadata["model"]))
        {
            $modelService=\Registry::getService("model");
            $model=$modelService->getModel($this->metadata["model"]);
            // Se crea una definicion, basada en los campos devueltos, no
            if(!isset($this->params["target"]))
                $this->params["target"]=$model->__getObjectName();
            $this->storage->createStorage($model,[],$this->params["target"]);
            $this->storageChecked=true;
        }
    }
}
