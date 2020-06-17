<?php

namespace model\ads\Comscore\serializers;

require_once(__DIR__.'/ComscoreDataSource.php');
require_once(__DIR__.'/storage/Comscore.php');
require_once(__DIR__.'/storage/QueryBuilder.php');


class ComscoreSerializerException extends \lib\model\BaseException
{
    const ERR_UNKNOWN_API = 1;
    const ERR_APÎ_CONNECTION = 2;
    const ERR_API_REQUEST = 3;
}

class ComscoreSerializer extends \lib\storage\StorageSerializer
{
 
    const COMSCORE_SERIALIZER_TYPE = "Comscore";
    const POLL_PAUSE   = 10;     // TODO: definir en configuración segundos entre llamadas
    const MAX_ATTEMPTS = 6*60*8; // TODO: definir en configuración reintentos máximos
    const BASE_DIR = '/vagrant/data/csv/'; // TODO: definir en configuración ruta base para los archivos
    
    protected $conn;
    protected $config;
    
    function __construct($definition, $useDataSpace=true)
    {
        global $Config;
        
        $this->config = $Config['SERIALIZERS']['comscore']['CONFIG'];
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
    
    function setRelation($table, $fixedSide, $srcValues)
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
    
    public function getSerializer()
    {
        if($this->serializer)
            return $this->serializer;
        $service=\Registry::getService("storage");
        if(isset($this->__objectDef["SERIALIZER"]))
        {
            if(is_array($this->__objectDef["SERIALIZER"]))
                $this->serializer=$service->getSerializer($this->__objectDef["SERIALIZER"]);
                else
                    $this->serializer=$service->getSerializerByName($this->__objectDef["SERIALIZER"]);
        }
        else
            $this->serializer= $service->getDefaultSerializer($this->objName);
            return $this->serializer;

    }
           
    
    function buildQuery($queryDef, $params, $pagingParams, $findRows=true)
    {
        $qB = new  \model\ads\Comscore\serializers\Comscore\storage\QueryBuilder($this, $queryDef, $params, $pagingParams);
        $qB->findFoundRows($findRows);
        return  $qB->build($queryDef["BASE"]);
    }
    
        
    protected function getReportsApi($q, $params, $pagingParams)
    {
        $result = $this->conn->request($q, "");
        
        $comscoreJob = json_decode($result);
        
        if (isset($comscoreJob->error)) {
            $comscoreJobId = $comscoreJob->error->details[0]->conflictedRecordId;
        } else {
            $comscoreJobId = $comscoreJob->data->id;
        }
        
        $dataReady = false;
        $attemps = 0;
        
        while(!$dataReady && $attemps<$this->config['API_MAX_ATTEMPTS']) {
            
            $attemps++;
            $waitQuery = ['BASE' => "ON Comscore CALL checkReport WITH (report_id='$comscoreJobId', region='[%region%]')"];
            
            $q = $this->buildQuery($waitQuery, $params, $pagingParams);
            $result = $this->conn->request($q, "");
            
            $comscoreJobStatus = (json_decode($result)->data->status=="COMPLETED");
            $dataReady = $comscoreJobStatus;
            if (!$dataReady) sleep($this->config['API_WAIT_TIMEOUT']);
        }
        
        $getQuery = ['BASE' => "ON Comscore CALL getReport WITH (report_id='$comscoreJobId', region='[%region%]')"];
        $q = $this->buildQuery($getQuery, $params, $pagingParams);
        $this->data = $data = $this->conn->request($q, "");
        
        $this->filename = $this->config['DATA_ROOT_DIR']."comscore_report_".time().".csv";
        $file = fopen($this->filename, "w+");
        $this->nRows = fwrite($file, $data);
        $this->__returnedFields = $this->definition['FIELDS'];
        fclose($file);
        
        $this->iterator = new \lib\model\types\DataSet(["FIELDS"=>$this->__returnedFields], $this->data,$this->nRows, $this->matchingRows, $this, $this->mapField);
        $this->__loaded=true;
        return $this->iterator;
    }
    
    protected function generateSoapParams($params)
    {
        $soapParams = [];
        foreach ($params as $param=>$values) 
        {
            foreach ((array)$values as $value) {
                $soapParams[] = [
                    'KeyId' => $param,
                    'Value' => $value,
                ];
            }
        }
        return ['query' => ['Parameter' => $soapParams] ];
    }
    
    protected function getMedia($q, $params, $pagingParams)
    {
        $result = $this->conn->request($q, "");
        $response = json_decode($result);
        /*foreach ($this->definition['FIELDS'] as $field=>$responseField) {
            $this->__returnedFields[$field] = $response->MediaItem->{$responseField};
        }*/
        return $response;
    }
    
    protected function getDemographicsApi($q, $params, $pagingParams)
    {
        
        try {
            
            $parameters = $q['soapParams']['parameters'];
            if (!$q['testing']) {
                $method = "SubmitReport";
                $q['soapParams']['parameters'] = ["parameterId" => "geo"];
                $q['soapParams']['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;
                $soapParams = $this->generateSoapParams($parameters);
            } else {
                $method = "TestMethodOne";
                $soapParams = $parameters;
            }
            
            $client = new \SoapClient($q["url"], $q['soapParams']);
            $result = $client->{$method}($soapParams);
            
            if (isset($result->SubmitReportResult->Errors))
            {
                throw new ComscoreSerializerException(ComscoreSerializerException::ERR_API_REQUEST);
            }
            
            if ($q['waitForResult']) {
                $params = ['jobId' => $result->SubmitReportResult->JobId];
                $waiting = true;
            } else {
                return $result; // TODO: formatear
            }
            
            while ($waiting) {
                 $result = $client->PingReportStatus($params);
                 if ($result->PingReportStatusResult->Status=="Completed") {
                     $waiting = false;
                 } else {
                     sleep(5);
                 }
            }
            $result = $client->FetchReport($params);
            $data = $this->__getSoapResults($result->FetchReportResult->REPORT);
            $this->data = $data['values'];
                
        } catch (\SoapFault $e) {
          echo $e->getMessage();
          echo $client->__getLastRequest();
          throw new ComscoreSerializerException(ComscoreSerializerException::ERR_API_CONNECTION);
        }
        
        $this->__returnedFields = $data['fields'];
        $this->iterator = new \lib\model\types\DataSet(["FIELDS"=>$this->__returnedFields], $this->data, $this->nRows, $this->matchingRows, $this, $this->mapField);
        $this->__loaded = true;
        return $this->iterator;
    }
    
    
    protected function createTable($obj, $level=1, $parent=null)
    {
        $htmlElement = "";
        if (is_object($obj)) {
            foreach($obj as $tag=>$sub) {
                if ($level==1 && strtoupper($tag)!=="TABLE")
                    continue;
                switch ($tag) {
                    case "TABLE":
                        $htmlElement .= "<$tag border=1>".$this->createTable($sub[0], $level+1)."</$tag>";
                        break;
                    case "_":
                        $htmlElement .=  $sub;
                        break;
                    case "id":
                        $htmlElement .= " ($sub)";
                        break;
                    case "TR":
                        if (is_array($sub)) {
                            $htmlElement .= $this->createTable($sub, $level+1, $tag);
                        } elseif (is_object($sub)) {
                            $htmlElement .= $this->createTable($sub->TH, $level+1, "TH");
                            $htmlElement .= $this->createTable($sub->TD, $level+1, "TD");
                        }
                        break;
                    case "TH":
                    case "TD":
                        if (is_array($sub)) {
                            $htmlElement .= $this->createTable($sub, $level+1, $tag);
                        } elseif (is_object($sub)) {
                            $htmlElement .= "<$tag>".$sub->_."</$tag>";
                        } 
                        break;
                    default:
                        $htmlElement .= "<$tag>".$this->createTable($sub, $level+1)."</$tag>";
                }
            }
        } elseif (is_array($obj)) {
            foreach($obj as $sub) {
                $htmlElement .= "<$parent>".$this->createTable($sub, $level+1)."</$parent>";
            }
        } else {
            $htmlElement = $obj;
        }
       
        
        return $htmlElement; 
    }
    
    protected function parseDemographicReport($obj)
    {
        $meta = [
            'title'     => $obj->TITLE,
            'subtitle'  => $obj->SUBTITLE,
            'base'      => $obj->SUMMARY->BASE,
            'country'   => $obj->SUMMARY->GEOGRAPHY,
            'media'     => $obj->SUMMARY->MEDIA,
            'month'     => $obj->SUMMARY->TIMEPERIOD,
        ];
        $report = [
            'fields'    => [],
            'rows'      => [],
        ];
        
        foreach ($obj->TABLE[0]->THEAD->TR[2]->TD as $field) {
            $report['fields'][] = $field->_;
        }
        
        foreach($obj->TABLE[0]->TBODY->TR as $rowData) {
            $row = [
                'meta'         => $meta,
                'filter_type'  => $rowData->TH[0]->_,
                'filter_value' => $rowData->TH[1]->_,
                'values'       => [],
            ];
            foreach ($rowData->TD as $index=>$value) {
                $row['values'][] = [
                    'name'  => $report['fields'][$index],
                    'value' => $value->_,
                ];
            }
            $report['rows'][] = $row;
        }
        return $report;
    }
    
    protected function __getSoapResults($soapData)
    {
        $data = [];
        echo $this->createTable($soapData);
        $report = $this->parseDemographicReport($soapData);
        $data['fields'] = $report['fields'];
        $data['values'] = $report['rows'];
        return $data;
    }
    
    function fetchAll($queryDef, &$data, &$nRows, &$matchingRows, $params, $pagingParams)
    {
        /*if(isset($queryDef["PRE_QUERIES"]))
        {
            foreach($queryDef["PRE_QUERIES"] as $cq)
                $this->conn->doQ($cq);
        }*/
        
        $q = $this->buildQuery($queryDef, $params, $pagingParams);
        
        switch (strtolower($q['api'])) {
            case "comscore":
                return $this->getReportsApi($q, $params, $pagingParams);
                break;
            case "comscoredemographics":
                if (empty($q["soapParams"])) {
                    return $this->getMedia($q, $params, $pagingParams);
                } else {
                    return $this->getDemographicsApi($q, $params, $pagingParams);
                }
                break;
            default:
                throw new ComscoreSerializerException(ComscoreSerializerException::ERR_UNKNOWN_API);
        }
        
        
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
