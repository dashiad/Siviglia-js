<?php

namespace model\ads\SmartConfig\serializers;

use lib\storage\TypeSerializer;

require_once(__DIR__.'/SmartConfigDataSource.php');
require_once(__DIR__.'/storage/SmartConfig.php');
require_once(__DIR__.'/storage/QueryBuilder.php');


class SmartConfigSerializerException extends \lib\model\BaseException
{
    const ERR_UNKNOWN_API = 1;
    const ERR_APÎ_CONNECTION = 2;
    const ERR_API_REQUEST = 3;
}

class SmartConfigSerializer extends \lib\storage\StorageSerializer
{
    
    public function __construct($definition, $serType=null) 
    {
        parent::__construct($definition, $serType);
        $this->serializerType = "smartconfig";
    }
    
    public function next()
    {}

    public function unserialize($object, $queryDef = null, $filterValues = null)
    {}

    public function fetchCursor($queryDef, &$data, &$nRows, &$matchingRows, $params, $pagingParams)
    {}

    public function destroyDataSpace($spaceDef)
    {}

    public function fetchAll($queryDef, &$data, &$nRows, &$matchingRows, $params, $pagingParams)
    {         
         $q = $this->buildQuery($queryDef, $params, $pagingParams);
         return [];
    }
    
    

    public function updateFromAssociative($target, $fields, $query)
    {}

    public function createStorage($modelDef, $extraDef = null)
    {}

    public function count($definition, &$model)
    {}

    public function getQueryBuilder($definition, $params)
    {}

    public function insertFromAssociative($target, $data)
    {}

    public function buildQuery($definition, $parameters, $pagingParameters, $getRows = true)
    {
        $qB = new  model\ads\SmartConfig\serializers\storage\QueryBuilder($this, $definition, $parameters, $pagingParameters);
        $qB->findFoundRows($findRows);
        return  $qB->build($queryDef["BASE"]);
    }

    public function existsDataSpace($name)
    {}

    public function useDataSpace($name)
    {}

    public function deleteByQuery($q, $params = null)
    {}

    public function createDataSpace($spaceDef)
    {}

    public function getTypeNamespace()
    {}

    public function subLoad($definition, &$relationColumn)
    {}

    public function destroyStorage($object)
    {}

    //
}