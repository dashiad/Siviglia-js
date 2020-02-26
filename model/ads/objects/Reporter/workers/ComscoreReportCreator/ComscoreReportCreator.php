<?php
namespace model\ads\Reporter\workers;

use model\web\Jobs\BaseWorker;
use model\ads\Reporter\workers\ComscoreReportCreator\ComscoreApi;
use model\ads\Reporter\workers\SmartXDownloader\ApiRequestException;
use lib\model\BaseTypedObject;

class ComscoreReportCreator extends BaseWorker
{
    
    const RESULT_DEFINITION = [
        'FIELDS' => [
            'start_date' => ['LABEL' => 'Fecha desde', 'TYPE' => 'Date'],
            'end_date'   => ['LABEL' => 'Fecha hasta', 'TYPE' => 'Date'],
            'file'       => ['LABEL' => 'Fichero', 'TYPE' => 'String'],
        ],
    ];
    
    protected static $defaultName = 'comscore_worker';
    
    protected function init()
    {
        $this->api = new ComscoreApi();
    }
    
    protected function runItem($item)
    { 
        
        $url = $this->getUrl();
        $params = $this->getParams($item);
        $region = $this->getRegion();
        
        try {
            $result = $this->api->createReportingJob($url, $params, $region);
            $data = json_decode($result['data'], true);
            $id = $data['data']['id'];
        } catch (ApiRequestException $e) {
            if ($e->getCode()==409) { 
                // existe ya un report idéntico, recogemos su id
                $data = json_decode($e->getResult());
                $id = $data->error->details[0]->conflictedRecordId;
            } else {
                throw $e;
            }
        }
        
        $waitForResultUntil = strtotime("+{$this->getTimeout()} seconds");
        $completed = false;
        while(time()<$waitForResultUntil) {
            $result = $this->api->getReportingJob($id, $region);
            $data = json_decode(($result['data']));
            if ($data->data->status=="COMPLETED") {
                $completed = true;
                break;
            }
            sleep(30);
        }

        if (!$completed)
            throw new \Exception("Remote job $id didn't finish in {$this->getTimeout()} seconds.");
        
        $result = $this->api->getReportingJobResult($id, $region);
        $resultFile = $this->saveCsv($result['data']);
        $resultData = $this->getResult($item, $resultFile);
        
        return $resultData->file;
    }
    
    protected function getStartdate($item)
    {
        if ($this->args['params']['type']=="None")
            return  $this->args['params']['params']['start_date'];
        else
            return $item;
    }

    protected function getEnddate($item)
    {
        if ($this->args['params']['type']=="None")
            return  $this->args['params']['params']['end_date'];
        else
            return $item;
    }
    
    
    protected function getResult($item, $filename)
    {
        $resultData = [
            'start_date' => $this->getStartdate($item),
            'end_date' => $this->getEnddate($item),
            'file' => $filename,
        ];
        $obj = new BaseTypedObject(self::RESULT_DEFINITION);
        $obj->loadFromArray($resultData);
        return $obj;
    }
    
    protected function getUrl()
    {
        $type = $this->args['params']['params']['type'];
        return "jobs/reporting/{$type}";
    }
    
    protected function getRegion()
    {
        return $this->args['params']['params']['region'];
    }
    
    protected function getTimeout()
    {
        return $this->args['params']['params']['timeout'];
    }
    
    protected function getParams($item) {
        
        $ViewByType = $this->args['params']['params']['view_by_type'];
        $campaignIds = $this->args['params']['params']['campaigns'];
        $startDate = $this->getStartdate($item);
        $endDate = $this->getEnddate($item);
        
        $params = [
            'campaignIds' => $campaignIds ,
            'ViewByType' => $ViewByType,
            'startDate' => date('m-d-Y', strtotime($startDate)),
            'endDate' => date('m-d-Y', strtotime($endDate))
        ];
        return $params;
    }
    
    protected function saveCsv(String $data) : ?String 
    {
        $fileName = __DIR__."/data/".$this->id.".csv";       
        $file = fopen($fileName, "w");
        fwrite($file, $data);
        fclose($file);
        return $fileName;
    }
    
}
