<?php
namespace lib\storage\Es;
include_once(LIBPATH."/datasource/DataSource.php");
    class ESDataSource extends \lib\datasource\StorageDataSource
    {
        var $nRows=0;
        var $matchingRows=0;
        var $reindexArray=array();
        var $joinBy;
        var $usingParsed=false;
        var $definitionInstance=null;
        function __construct($objName,$dsName,$definitionInstance,$serializer,$serializerDefinition=null)
        {

            if($serializerDefinition)
                $this->serializerDefinition=$serializerDefinition;
            $this->serializer=$serializer;
            $this->definitionInstance=$definitionInstance;
            \lib\datasource\StorageDataSource::__construct($objName,$dsName,$definitionInstance::$definition,$serializer);
        }

        function getIterator($rowInfo=null)
        {
            if(!$this->iterator)
            {
                $this->iterator=$this->fetchAll();
            }
            if($this->mapField)
            {
                // TODO : Solo permite hacer join por el primer campo del joinBy
                //$keys=array_keys($this->joinBy);
                $this->iterator->setIndex($rowInfo[$this->parentField]);
            }
            return $this->iterator;
        }
        function count(){
            return $this->matchingRows;
        }
        function countColumns(){}
        function getMetaData(){}

        function setSerializer($serializer)
        {
            $this->serializer=$serializer;
        }
    }

