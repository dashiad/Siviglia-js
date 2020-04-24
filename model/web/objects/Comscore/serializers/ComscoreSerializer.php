<?php

namespace model\web\Comscore\serializers;

require_once(__DIR__.'/storage/Comscore.php');
require_once(__DIR__.'/ComscoreDataSource.php');

use lib\php\ParametrizableString;
use lib\datasource\DataSource;
use model\web\Comscore\serializers\Comscore\storage\Comscore;
use model\web\Comscore\datasources\ComscoreDataSource;

class ComscoreSerializerException extends \lib\model\BaseException
{
    const ERR_DEFAULT = 1;
}

class ComscoreSerializer extends \lib\storage\StorageSerializer
{
    
    protected $conn;
    
    const COMSCORE_SERIALIZER_TYPE = "Comscore";
    
    function __construct($definition, $useDataSpace=true)
    {
        $this->conn = new  \model\web\Comscore\serializers\Comscore\storage\Comscore($definition);
        \lib\storage\StorageSerializer::__construct($definition, static::COMSCORE_SERIALIZER_TYPE);         
    }
    
    function unserialize($object, $queryDef=null, $filterValues=null)
    {
        echo "unserialize";
        //
    }
    
    
    function delete($objects, $basedOnFields=null, $tableName=null)
    {
        //
        echo "delete";
    }
    
    function deleteByQuery($q,$params=null)
    {
        //
        echo "deleteByQuery";
    }
    
    function add($objects, $tableName=null)
    {
        //
        echo "add";
    }
    
    function update($object, $byFields=[], $tableName=null)
    {
        //
        echo "update";
    }
    
    function setRelation($table, $fixedSide, $variableSides, $srcValues)
    {
        //
    }
    
    function subLoad($definition, &$relationColumn)
    {
        //
    }
    
    function count($definition, &$model, $table=null)
    {
        //
    }
    
    function createStorage($modelDef, $extraDef = null,$tableName=null)
    {
        //
    }
    
    function destroyStorage($object,$tableName=null)
    {
        //
    }
    
    function createDataSpace($spaceDef)
    {
        //
    }
    
    function existsDataSpace($spaceDef)
    {
        //
    }
    
    function destroyDataSpace($spaceDef)
    {
       //
    }
    
    function useDataSpace($dataSpace)
    {
       //
    }
    function getCurrentDataSpace()
    {
        //
    }
    
    public function getDatasourceClassName() : String
    {
        return ComscoreDataSource::class;
    }
    
    function buildQuery($queryDef,$params,$pagingParams,$findRows=true)
    {
        $qB = new QueryBuilder($this,$queryDef, $params,$pagingParams);
        $qB->findFoundRows($findRows);
        return  $qB->build();
    }
    
    function fetchAll($queryDef, &$data, &$nRows, & $matchingRows, $params, $pagingParams)
    {
        if(isset($queryDef["PRE_QUERIES"]))
        {
            foreach($queryDef["PRE_QUERIES"] as $cq)
                $this->conn->doQ($cq);
        }
        $q=$this->buildQuery($queryDef,$params,$pagingParams);
        //echo $q."<br>";
        $data = $this->conn->selectAll($q, $nRows);
        
        $frows = $this->conn->select("SELECT FOUND_ROWS() AS NROWS");
        $matchingRows = $frows[0]["NROWS"];
    }
    
    function fetchCursor($queryDef, & $data, & $nRows, & $matchingRows, $params, $pagingParams)
    {
        if(isset($queryDef["PRE_QUERIES"]))
        {
            foreach($queryDef["PRE_QUERIES"] as $cq)
                $this->conn->doQ($cq);
        }
        $q=$this->buildQuery($queryDef,$params,$pagingParams,false);
        //echo $q."<br>";
        $this->currentCursor =  $this->conn->cursor($q);
        $nRows=0;
        $matchingRows=0;
    }
    
    function next()
    {
//         if($this->currentCursor)
//             return $this->conn->fetch($this->currentCursor);
//             return null;
    }
    
    function getConnection()
    {
        //return $this->conn;
    }
    
    function processAction($definition, $parameters)
    {
        $qB = new QueryBuilder($definition, $parameters);
        $q = $qB->build();
        //$this->conn->doQ($q);
        var_dump($q);
    }
    
    function getTypeNamespace()
    {
        return __NAMESPACE__ .'\\types';
    }
    
    function getQueryBuilder($conds, $params)
    {
        return new QueryBuilder($this, $conds, $params);
    }
    
    function insertFromAssociative($target, $data)
    {
        $result = $this->conn->request($data);
        return $result;
    }
    
    function updateFromAssociative($target, $fields, $query)
    {
        //
    }
    
    function updateOnSaveFields($object, $setOnSaveFields, $isNew)
    {
        foreach($setOnSaveFields as $k=>$v)
        {
            if(!$v->is_set())
                $pending[] = $k;
        }        
    }
}
