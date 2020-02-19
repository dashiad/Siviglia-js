<?php
namespace model\web\Jobs\App\Jobs\Splitters;

use model\web\Jobs;

class RunningJobsSplitter implements SplitterInterface
{
    const DEFAULT_CHUNK_SIZE = 9999999;
    
    public function get(Array $params) : Array
    {
        $max_chunk_size = $params['max_chunk_size'] ?? self::DEFAULT_CHUNK_SIZE;
        $serService = \Registry::getService("storage");
        $serializer = $serService->getSerializerByName("web");
        $datasource = \lib\datasource\DataSourceFactory::getDataSource("/model/web/Jobs", "FullList", $serializer);
        $datasource->status = Jobs::RUNNING;
        $result =$datasource->fetchAll();
        $runningJobs = $result->value;
        return array_chunk($runningJobs, $max_chunk_size);
    }    
}