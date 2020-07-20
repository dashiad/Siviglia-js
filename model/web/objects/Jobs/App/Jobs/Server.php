<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\Runnables\Job;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Interfaces\StatusInterface;

class Server implements StatusInterface
{    
    protected $id;
    protected $host;
    protected $port;
    protected $control;
    protected $main;
    
    protected $http;
    protected $queue;
    protected $manager;
    
    const SERVER_ID = "jobs_api";
    const ACTION_PATTERN = "/^\/[0-9a-zA-Z-_.]*\/[a-z_-]*\/?$/";
    
    public function __construct()
    {
        $this->id      = self::SERVER_ID;
        $this->host    = Config::get('main', 'server_host');
        $this->port    = Config::get('main', 'server_port');
        $this->control = Config::get('queue', 'control');
        $this->main    = Config::get('queue', 'main');
        $this->manager = Config::get('main', 'app');
        
        $this->http = new \swoole_http_server($this->host, $this->port);
        $this->http->set(Config::get('main', 'http'));
    }
    
    public function init()
    {
        $this->http->on('Start', function ($server) {
            if (DEBUG) echo "Job server ($this->id) started and listening at http://$this->host:$this->port with PID ".getmypid().PHP_EOL;
        });
        
        $this->http->on('Shutdown', function ($server) {
            if (DEBUG) echo "Stopping server with ".getmypid().PHP_EOL;
        });

        $this->http->on('ManagerStart', function ($server) {
            if (DEBUG) echo "Started manager with ".getmypid().PHP_EOL;
        });
        
        $this->http->on('ManagerStop', function ($server) {
            if (DEBUG) echo "Stopping manager with ".getmypid().PHP_EOL;
        });
        
        $this->http->on('WorkerStart', function ($server, $workerId) {
            if (DEBUG) echo "Started worker #$workerId with pid ".getmypid().PHP_EOL;
            $this->queue = Queue::connect($this->id, false);
            $this->channel = $this->queue->getDefaultChannel();
        });
    
        $this->http->on('WorkerStop', function ($server, $workerId) {
            if (DEBUG) echo "Stopping worker #$workerId with pid ".getmypid().PHP_EOL;
            unset($this->queue);
        });
        
        $this->http->on('Task', function ($server, $taskId, $workerId, $data) {
            if (DEBUG) echo "Created $taskId in #$workerId with pid ".getmypid().PHP_EOL;
        });
    
        $this->http->on('request', function ($request, $response) {            
            $content = json_decode($request->rawContent(), true);
            $uri = $request->server['request_uri'];
            $method = $request->server['request_method'];

            if (DEBUG) echo "$method request received $uri".PHP_EOL;
            
            $result = null;
            
            if($method=="POST" && $uri="/") {
                $result = $this->create($content);
            }
            
            if($method=="GET" && $uri=="/status") {
                $result = $this->send('job_manager', 'status');
            }
            
            if($result===null && $method=="GET" && preg_match(self::ACTION_PATTERN, $uri)) {
                $result = $this->action($uri);
            }
            
            if(is_null($result)) {
                $result = json_encode(['message' => 'Wrong request']);
            }
            
            echo $result.PHP_EOL;
            
            $response->header("Content-Type", "application/json");
            $response->end($result);
        });
        try {
            $this->http->start();
        } catch (\Exception $e) {
            echo CLI::colorStr($e->getMessage(), 'red', 'white');
        }
    }
        
    protected function create($content)
    {        
        $content['job_id'] = uniqid($content['name'].'_');
        $msg = new SimpleMessage([
            'from'   => $this->id,
            'to'     => $this->manager,
            'action' => "create",
            'data'   => $content,
        ]);
        $this->queue->publish($msg, $this->channel, 'control');
        
        return json_encode($msg->data);
    }
    
    protected function action($uri)
    {
        $data = explode('/', $uri);
        $id = $data[1];
        $action = $data[2];        
        return $this->send($id, $action);
    }
    
    protected function send($id, $action)
    {
        $msg = new SimpleMessage([
            'from'   => $this->id,
            'to'     => $id,
            'action' => $action,
        ]);
        
        $this->queue->publish($msg, $this->channel, 'control', $id);
        return $msg->toJson();
    }
    
}
