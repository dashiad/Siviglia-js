<?php
namespace model\ads\Reporter\workers;

use model\web\Jobs\BaseWorker;
use model\ads\Reporter\workers\SmartXDownloader\SmartXApi;

class SmartXDownloader extends BaseWorker
{
    protected static $defaultName = 'smartx_downloader';
    protected $conn;
    
    protected function init()
    {
        $this->conn = SmartXApi::getInstance();
    }
    
    protected function runItem($item)
    {
        $call = $item["call"];
        $params = $item["params"];
        $options = [];
        $result = $this->conn->getAll($call, $params, "GET", $options);
        $filename = str_replace("/", "_", $call)."_".time().".csv";
        $this->saveFile($result['data'], $filename);
        return $filename;
    }
    
    protected function saveFile($result, $filename, $includeHeader=true)
    {
        $file = fopen(__DIR__."/data/$filename", "w");
        $headerWritten = false;
        if (count($result)>0) {
            foreach ($result as $line) {
                if ($includeHeader && !$headerWritten) {
                    fputcsv($file, array_keys($line), ";");
                    $headerWritten = true;
                }
                foreach($line as $key=>$value) {
                    if (is_array($value))
                        $line[$key] = json_encode($value);
                }
                fputcsv($file, $line, ";");
            }
        } else {
            fwrite($file, "no data");
        }
        fclose($file);
        return $result;
    }
    
}