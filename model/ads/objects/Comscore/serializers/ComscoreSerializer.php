<?php

namespace model\ads\Comscore\serializers;

require_once(__DIR__.'/ComscoreDataSource.php');
require_once(__DIR__.'/storage/Comscore.php');
require_once(__DIR__.'/storage/QueryBuilder.php');

use lib\php\ParametrizableString;
use lib\datasource\DataSource;
use model\ads\Comscore\serializers\Comscore\storage\Comscore;
use model\ads\Comscore\datasources\ComscoreDataSource;

class ComscoreSerializerException extends \lib\model\BaseException
{
    const ERR_DEFAULT = 1;
}

class ComscoreSerializer extends \lib\storage\StorageSerializer
{
 
    const COMSCORE_SERIALIZER_TYPE = "Comscore";
    const POLL_PAUSE   = 10;     // TODO: definir en configuración segundos entre llamadas
    const MAX_ATTEMPTS = 6*60*8; // TODO: definir en configuración reintentos máximos
    const BASE_DIR = '/vagrant/data/csv/'; // TODO: definir en configuración ruta base para los archivos
    
    protected $conn;
    
    function __construct($definition, $useDataSpace=true)
    {
        $this->conn = new  \model\ads\Comscore\serializers\Comscore\storage\Comscore($definition);
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
    
    function buildQuery($queryDef, $params, $pagingParams, $findRows=true)
    {
        $qB = new  \model\ads\Comscore\serializers\Comscore\storage\QueryBuilder($this, $queryDef, $params, $pagingParams);
        $qB->findFoundRows($findRows);
        return  $qB->build($queryDef["BASE"]);
    }
    
    function fetchAll($queryDef, &$data, &$nRows, &$matchingRows, $params, $pagingParams)
    {
        /*if(isset($queryDef["PRE_QUERIES"]))
        {
            foreach($queryDef["PRE_QUERIES"] as $cq)
                $this->conn->doQ($cq);
        }*/
            
        $q = $this->buildQuery($queryDef, $params, $pagingParams);
        $result = $this->conn->request($q, "");
        
        $comscoreJob = json_decode($result);
        
        if (isset($comscoreJob->error)) {
            $comscoreJobId = $comscoreJob->error->details[0]->conflictedRecordId;
        } else {
            $comscoreJobId = $comscoreJob->data->id;
        }
        
        $dataReady = false;
        $attemps = 0;
        
        while(!$dataReady && $attemps<static::MAX_ATTEMPTS) {
            
            $attemps++;
            $waitQuery = ['BASE' => "ON Comscore CALL checkReport WITH (report_id='$comscoreJobId', region='[%region%]')"];
            
            $q = $this->buildQuery($waitQuery, $params, $pagingParams);
            $result = $this->conn->request($q, "");
            
            $comscoreJobStatus = (json_decode($result)->data->status=="COMPLETED");
            $dataReady = $comscoreJobStatus;
            if (!$dataReady) sleep(static::POLL_PAUSE);
        }
        
        $getQuery = ['BASE' => "ON Comscore CALL getReport WITH (report_id='$comscoreJobId', region='[%region%]')"];
        $q = $this->buildQuery($getQuery, $params, $pagingParams);
        $this->data = $data = $this->conn->request($q, "");
        
        $this->filename = static::BASE_DIR."comscore_report_".time().".csv";
        $file = fopen($this->filename, "w+");
        $this->nRows = fwrite($file, $data);
        $this->__returnedFields = $this->definition['FIELDS'];
        fclose($file);
        
        // TO-DO: revisar
        $this->iterator=new \lib\model\types\DataSet(["FIELDS"=>$this->__returnedFields], $this->data,$this->nRows, $this->matchingRows, $this, $this->mapField);
        $this->__loaded=true;
        return $this->iterator;
        
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
    
    public function getPagingParameters()
    {
        return [];
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
