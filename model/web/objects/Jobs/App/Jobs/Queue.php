<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager;

class Queue
{
    public static function connect(String $id) : AbstractQueueManager
    {
        $className = Config::get('queue', 'class');
        return new $className($id);
    }
    
    public static function addChannel(AbstractQueueManager $queue)
    {
        return $queue->addChannel();
    }
    
    public static function create(String $id, Bool $autodelete=true) : AbstractQueueManager
    {
        $conn = self::connect($id);
        //$channelId = $conn->createChannel($id);
        return $conn;
    }
}