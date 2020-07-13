<?php
namespace model\web\Jobs\App\Jobs\QueueManagers;

use model\web\Jobs\App\Jobs\Config;
use model\web\Jobs\App\Jobs\Messages\BaseMessage;

abstract class AbstractQueueManager
{
    
    protected static $config;
    protected static $type = '';
    protected static $maxChannels = 1;
    
    protected $connection;
    protected $queue;
    protected $id;
    protected $subscriptions = [];
    protected $channels = [];
    protected $queues = [];
    
    
    public function __construct()
    {
        static::init();
        $this->connect();
    }
    
    public function __destruct()
    {
        $this->disconnect();
    }
    
    static protected function init()
    {
        if (empty(static::$config)) {
            static::$config = Config::get(static::$type);
        }
    }
    
    public function addChannel() : int
    {
        if (count($this->channels)<static::$maxChannels) {
            return $this->createChannel();
        } else {
            throw new \Exception("Se ha alcanzado el número máximo de canales en esta conexión");
        }
    }
    
    public function getChannel($index)
    {
        return $this->channels[$index];
    }
    
    public function getChannels()
    {
        return $this->channels;
    }
    
    abstract public function connect();
    abstract public function disconnect();
    abstract public function getDefaultChannel() : Int;
    abstract public function createChannel() : Int;
    abstract public function deleteChannel(Int $index);
    abstract public function createQueue(String $id, Int $channel, Bool $autodelete=true);
    abstract public function deleteQueue(String $id, Int $channel);
    abstract public function subscribe($subscription=[], Int $channel, String $queue, String $routingKey='');
    abstract public function publish(BaseMessage $msg, Int $channel, String $queue='', String $key='');
    abstract public function listen($listener, Int $channel);
    abstract public function stopListening($listener, Int $channel);
    /**
     *
     * Returns received message as array 
     * 
     * @param mixed $msg
     * @return Array
     */
    abstract public function extractMessage($msg) : Array;
}
