<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Runnables\Task;

class Dispatcher
{
    protected $id;
    protected $queue;
    
    const WORKER = __DIR__ . '/../bin/worker.php';
    
    public function __construct()
    {
        $this->id = 'dispatcher';
        $this->queue = Queue::create($this->id, false);
        $this->queue->createQueue($this->id, $this->queue->getDefaultChannel(), false);
        $this->control = Config::get('queue', 'control');
        $this->queue->subscribe('dispatch', $this->queue->getDefaultChannel(), 'dispatcher');
        
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
        if ($msg->action=='dispatch') {
            $task = new Task($msg->data);
            //$workerClass = JOBS_NAMESPACE.'Workers\\'.$task->type.'Worker';
            $workerClass = $task->type;
            $process = new \swoole_process(function ($process) use ($workerClass, $task) {
                $process->exec(PHP_BINARY, [self::WORKER, $workerClass, json_encode($task->toarray())]);
            });
            $pid = $process->start();
            if (DEBUG) echo "Lanzado proceso con pid $pid".PHP_EOL;
            $process->wait(false);
        } else {
            echo "descarto un ".$msg->action." para ".$msg->to.PHP_EOL;
        }
        return false;
    }
}

