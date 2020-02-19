<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Runnables\Task;
use Swoole\Process;

class Dispatcher
{
    protected $id;
    protected $queue;  
    protected $processes = [];
    
    const WORKER = __DIR__ . '/../bin/worker.php';
    
    const ACTIONS = [
        'dispatch',
        'worker_kill',
    ];
    
    const WORKER_KILL_MAX_TRIES = 16; // max requeues to kill each children
    
    public function __construct()
    {
        $this->id = 'dispatcher';
        $this->queue = Queue::create($this->id, false);
        //$this->queue->createQueue($this->id, $this->queue->getDefaultChannel(), true);
        //$this->queue->createQueue($this->id, $this->queue->getDefaultChannel(), false);
        $this->control = Config::get('queue', 'control');
        $this->queue->subscribe('dispatch', $this->queue->getDefaultChannel(), 'dispatcher');
        //$this->queue->subscribe('dispatch', $this->queue->getDefaultChannel(), $this->id);
        //$this->queue->subscribe('control', $this->queue->getDefaultChannel(), $this->id);
        
        $msg = new SimpleMessage();
        $msg->from = $this->getId();
        $msg->to = "*";
        $msg->action = "dispatcher_start";
        
        $this->queue->publish($msg, $this->queue->getDefaultChannel(), $this->control);
        $this->init();
    }

    public function init()
    {
        $pid = getmypid();
        echo "Dispatcher started with pid $pid".PHP_EOL;
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
            //if (DEBUG) echo CLI::colorStr("descarto un ".$msg->action." para ".$msg->to, "yellow").PHP_EOL;
        }
        return false;
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
        if (!isset($this->processes[$parent]))
            $this->processes[$parent]=[];
        $this->processes[$parent][$index] = $process;
        $debug = $process->wait(false);
    }
    
    protected function worker_kill($msg)
    {
        $parent = $msg->from;
        $index = $msg->params['index'];
        if (isset($this->processes[$parent][$index])) {
            $process = $this->processes[$parent][$index];
            if (DEBUG) echo CLI::colorStr("Killing $parent($index) pid $process->pid", "yellow").PHP_EOL;
            Process::kill($process->pid, SIGTERM);
            unset($this->processes[$parent][$index]);
        } else {
            if ($msg->params['ttl']>time() && $msg->params['try']<self::WORKER_KILL_MAX_TRIES) {
                if (DEBUG) echo CLI::colorStr("Requeue kill $parent($index) (not found)", "yellow").PHP_EOL;
                $params = $msg->params;
                $params['try']++;
                $msg->params = $params;
                $this->queue->publish($msg, $this->queue->getDefaultChannel(), 'dispatch'); // reenvío
            } else {
                if (DEBUG) echo CLI::colorStr("Discarded kill $parent($index) (not found)", "white", "red").PHP_EOL;
            }
        }
    }
}

