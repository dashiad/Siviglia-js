<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Runnables\Task;
use Swoole\Process;
use Swoole\Table;

class Dispatcher
{
    protected $id;
    protected $queue;  
    protected $processes = [];
    protected $sharedQueue;
    protected $dispatcherProcess;
    protected $control;
    
    protected $table;
    
    const WORKER = __DIR__ . '/../bin/worker.php';
    
    const ACTIONS = [
        'dispatch',
        'worker_kill',
    ];
    
    const WORKER_KILL_MAX_TRIES = 16; // max requeues to kill each children
    
    public function __construct()
    {
        //$this->id = 'dispatcher';
        $this->id = uniqid('dispatcher_');
        $this->queue = Queue::create($this->id, false);
        $this->sharedQueue = Queue::create("dispatcher", false);
        
        $this->control = Config::get('queue', 'control');
        $this->queue->subscribe('dispatch', $this->queue->getDefaultChannel(), $this->id, $this->id);
        $this->queue->subscribe('dispatch', $this->queue->getDefaultChannel(), $this->id, "broadcast");
        $this->sharedQueue->subscribe('dispatch', $this->sharedQueue->getDefaultChannel(), "dispatcher");
        
        $msg = new SimpleMessage();
        $msg->from = $this->getId();
        $msg->to = "*";
        $msg->action = "dispatcher_start";
        
        $this->queue->publish($msg, $this->queue->getDefaultChannel(), $this->control);
        
        $this->createProcessTable();
        
        $this->init();
           
    }

    protected function createProcessTable()
    {
        $this->table = new Table(1024);
        $this->table->column('pid', Table::TYPE_INT);
        $this->table->create();
    }
    
    public function init()
    {
        $pid = getmypid();
        echo "Dispatcher started with pid $pid".PHP_EOL;
        
        $process = new Process(function ($worker) {
            $this->sharedQueue->listen($this, $this->sharedQueue->getDefaultChannel(), "dispatcher");
        });
        $pid = $process->start();
        $this->dispatcherProcess = $process;
        $this->dispatcherProcess->wait(false);
        $this->queue->listen($this, $this->queue->getDefaultChannel());
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function handle($args)
    {
        $data = json_decode($args->body, true);
        $className = JOBS_NAMESPACE.'Messages\\'.$data['msg_type'];
        $msg = new $className($data);
        if (in_array($msg->action, self::ACTIONS)) {
            $action = $msg->action;
            $this->$action($msg);
        } else {
            if (DEBUG) echo CLI::colorStr("descarto un ".$msg->action." para ".$msg->to, "yellow").PHP_EOL;
        }
        return false;
    }
    
    protected function registerWorker(String $parent, Int $index, Process $process)
    {
        /*if (!isset($this->processes[$parent])) $this->processes[$parent] = [];
        $this->processes[$parent][$index] = $process;*/
        $this->table["$parent.$index"] = ["pid" => $process->pid];
    }
    
    protected function unRegisterWorker(String $parent, Int $index) 
    {
        /*if ($this->getWorker($parent, $index)) {
            unset($this->processes[$parent][$index]);
                if (count($this->getJobWorkers($parent))==0)
                    unset($this->processes[$parent]);
        }*/
        $this->table->del["$parent.$index"];
    }
    
    protected function getWorker(String $parent, Int $index) : ?Int
    {
        return $this->table->get("$parent.$index", "pid");
    }
    
    protected function dispatch($msg)
    {
        $task = new Task($msg->data);
        $workerClass = $task->type;
        $parent = $task->parent;
        $index = $task->index;
        $process = new Process(function ($worker) use ($workerClass, $task) {
            $worker->exec(PHP_BINARY, [self::WORKER, $workerClass, json_encode($task->toarray())]);
        });
        $pid = $process->start();
        if (DEBUG) echo "Lanzado proceso con pid $pid".PHP_EOL;
        $this->registerWorker($parent, $index, $process);
        $process->wait(false);
    }
    
    protected function worker_kill($msg)
    {
        $parent = $msg->from;
        $index = $msg->params['index'];
        $pid = $this->getWorker($parent, $index);
        if ($pid) {
            if (DEBUG) echo CLI::colorStr("Killing $parent($index) pid $pid", "yellow").PHP_EOL;
            Process::kill($pid, SIGTERM);
            $this->unRegisterWorker($parent, $index);
        } else {
            if (DEBUG) echo CLI::colorStr("Discarded kill $parent($index) (not found)", "white", "red").PHP_EOL;
        }
    }
}

