<?php
namespace model\ads\Reporter\workers;

use model\web\Jobs\BaseWorker;
use model\ads\Reporter\workers\ComscoreReportCreator\ComscoreApi;
use model\ads\Reporter\workers\SmartXDownloader\ApiRequestException;

class ComscoreReportCreator extends BaseWorker
{
    protected static $defaultName = 'comscore_worker';
    
    protected function init()
    {
        $this->api = new ComscoreApi();
    }
    
    protected function runItem($item)
    { 
        $type = $this->args['params']['params']['type'];
        $ViewByType = $this->args['params']['params']['view_by_type'];
        $region = $this->args['params']['params']['region'];
        $timeout = $this->args['params']['params']['timeout'];
        $campaignIds = $this->args['params']['params']['campaigns'];
        
        if ($this->args['params']['type']=="None") {
            $startDate =  $this->args['params']['params']['start_date'];
            $endDate = $this->args['params']['params']['end_date'];
        } else {
            $startDate =  $item;
            $endDate   = $item;
        }
     
        $url = "jobs/reporting/{$type}";
        
        $params = [
            'campaignIds' => $campaignIds ,
            'ViewByType' => $ViewByType,
            'startDate' => date('m-d-Y', strtotime($startDate)),
            'endDate' => date('m-d-Y', strtotime($endDate))
        ];
        
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
        
        $waitForResultUntil = strtotime("+$timeout seconds");
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
            throw new \Exception("Remote job $id didn't finish in $timeout seconds.");
        
        $result = $this->api->getReportingJobResult($id, $region);
        return $this->saveCsv($result['data']);
       
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
