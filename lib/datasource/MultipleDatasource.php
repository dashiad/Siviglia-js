<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Usuario
 * Date: 13/10/13
 * Time: 0:46
 * To change this template use File | Settings | File Templates.
 */

namespace lib\datasource;

class MultipleDatasourceIterator
{
    protected $data;
    protected $iterators;
    function __construct($datasources,$definition)
    {
        $this->data=$datasources;
        $this->definition=$definition;
        $this->iterators=null;
    }
    function __get($varName)
    {
        if(!isset($this->iterators[$varName]))
        {
            if(!isset($this->data[$varName]))
                return null;
            $this->iterators[$varName]=$this->data[$varName]->getIterator();
        }
        return $this->iterators[$varName];
    }
    function getFullData()
    {
        $result=array();
        foreach($this->data as $key=>$value)
        {
            $it=$this->{$key};
            $result[$key]=$it->getFullData();
            
            if(isset($this->definition["DATASOURCES"][$key]["LOAD_INCLUDES"]))
            {
                $includes=$it->getDataSource()->getSubDataSources();
                if($includes)
                {
                    foreach($includes as $key2)
                    {
                        $result[$key2]=$it->getField($key2)->getFullData();
                    }
                }

            }

        }
        return $result;
    }
}

class MultipleDatasource {
    var $definition;
    var $serializer;
    var $serializerDefinition;
    var $params;
    var $iterator;
    var $datasources;
    function __construct($objName,$dsName,$definition)
    {

        $this->definition=$definition;
        $this->params=null;
        foreach($this->definition["DATASOURCES"] as $key=>$value)
        {
            $this->datasources[$key]=\lib\datasource\DataSourceFactory::getDataSource($value["MODEL"],$value["DATASOURCE"]);
        }
    }
    function setParameters($params)
    {
        $this->params=$params;


    }
    function fetchAll(){ return $this->getIterator();}
    function getIterator($rowInfo=null)
    {
        if( !$this->iterator )
        {

            if($this->params)
            {
                foreach($this->datasources as $key=>$value)
                {
                    $value->setParameters($this->params);
                }
            }
            $this->iterator= new MultipleDatasourceIterator($this->datasources,$this->definition);
        }
        return $this->iterator;

    }
    function count()
    {
        return count($this->definition["DATASOURCES"]);
    }
    function countColumns()
    {
        return $this->count();
    }
    function getMetaData()
    {
        $meta=array();
        foreach($this->datasources as $key=>$value)
        {
            $meta[$key]=$value->getMetaData();
            if($value["LOAD_INCLUDES"])
            {
                $it=$value->getIterator();
                $includes=$value->getSubDataSources();
                if($includes)
                {
                    foreach($includes as $key2)
                    {
                        $meta[$key2]=$it->getField($key2)->getMetaData();
                    }
                }
            }
        }
        return $meta;
    }
    function getOriginalDefinition()
    {
        $def=$this->definition;
        foreach($this->datasources as $key=>$value)
        {
            $def["DATASOURCES"][$key]["DEFINITION"]=$value->getOriginalDefinition();
            if(isset($this->definition["DATASOURCES"][$key]["LOAD_INCLUDES"]))
            {
                $includes=$value->getSubDataSources();

                if($includes)
                {
                    foreach($includes as $key2)
                    {
                        $include=$value->getSubDataSourceInstance($key2);
                        $def["DATASOURCES"][$key2]["DEFINITION"]=$include->getOriginalDefinition();
                    }
                }
            }
        }
        return $def;
    }
}

