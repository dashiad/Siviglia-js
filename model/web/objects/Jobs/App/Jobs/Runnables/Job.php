<?php
namespace model\web\Jobs\App\Jobs\Runnables;

use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Split;
use model\web\Jobs\App\Jobs\Persistable;
use model\web\Jobs\App\Jobs\CLI;

class Job extends AbstractRunnable
{

    use Persistable;
    
    const CHILDREN_KILL_TTL      = 5;   // seconds to try to kill each children
    
    protected $prefix = 'job_';
    protected $actions = [
        'dispatch'   => 'dispatch',
        'job_finish' => 'job_finish',
    ];
 
    protected $tasks = [];
    
    //protected $modelName = 'model\\web\\Jobs\\Jobs';
    protected $modelName = 'model\\web\\Jobs';
    protected $fields = [
        'job_id'     => 'id',
        'name'       => 'name',
        'status'     => 'status',
        'parent'     => 'parent',
        'descriptor' => 'args', 
    ];
    protected $maxRetries = 0; // default: no retries
    protected $waitingForChildren = false;
    protected $killed = false;
    
    protected function initialize()
    {   
        if (isset($this->args['max_retries'])) {
            $this->maxRetries = $this->args['max_retries'];
        }
        
        if (isset($this->args['jobs'])) {
            $this->createJobs($this->args['jobs']);
        }
        elseif (isset($this->args['task'])) {
            $this->createTasks($this->args['task']['args']);
        } elseif (isset($this->args['partials'])) {
            $this->waitingForChildren = true;
        } else {
            throw new \Exception("El trabajo debe contener jobs o task");
        }
        $this->persist();
    }
    
    protected function createJobs(Array $jobs)
    {
        foreach ($jobs as $index=>$job) {
            //$job['type'] = 'job';
            $job['parent'] = $this->id;
            $job['job_id'] = $this->id.".".uniqid($job['name']."_");
            $job['index']  = $index;
            
            $msg = new SimpleMessage([
                'from'   => $this->id,
                'to'     => $this->manager,
                'action' => "create",
                'data'   => $job,
            ]);
            $this->queue->publish($msg, $this->channel, 'control');
            $this->addChildren($job, 'job');
        }
        foreach($this->children as $index=>$child) echo $child['index'];
    }
    
    protected function createTasks(Array $task)
    {
        $chunks = Split::get($task);
        foreach ($chunks as $index=>$chunk) {
            $newTask = new Task();
            $newTask->parent = $this->id;
            $newTask->status = self::WAITING;
            $newTask->type   = $task['task'];
            $newTask->index  = $index;
            $newTask->items  = $chunk;
            $newTask->number_of_parts = count($chunks);
            $newTask->params = $task;
            $newTask->waitingForTrigger = false;
            $this->addChildren($newTask->toArray(), 'task');
        }
    }
    
    public function addEmptyChildren($number)
    {
        if ($this->waitingForChildren) {
            $lock = new \swoole_lock(SWOOLE_MUTEX);
            $lock->lock();
            $task = [
                'parent' => $this->id,
                'status' => self::WAITING,
                'type'   => $this->args['partials']['type'],
                'index'  => null,
                'items'  => null,
                'number_of_parts' => $number,
                'params' => $this->args['partials'],
                'waiting_for_trigger' => true,
            ];
            for($i=0;$i<$number;$i++) {
                $task['index'] = $i;
                $this->addChildren($task, 'task');
            }
            $this->waitingForChildren = false;
            $lock->unlock();
        }
    }
    
    private function addChildren($children, String $type)
    {
        $this->children[] = [
            'type'   => $type,
            'status' => self::WAITING,
            'data'   => $children,
            'try'    => 0,
        ];
    }
    
    public function start()
    {
        $this->status=self::RUNNING;
        foreach(array_keys($this->children) as $index) {
            if (!$this->children[$index]['waiting_for_trigger'])
                $this->children[$index]['status'] = self::PENDING;
        }
        $this->startChildren();
        $this->persist();
    }
    
    public function kill()
    {
        if ($this->status==self::RUNNING) {
            $lock = new \swoole_lock(SWOOLE_MUTEX);
            $lock->lock();
            foreach(array_keys($this->children) as $index) {
                if ($this->children[$index]['status']==self::RUNNING) {
                    $args = [
                        'sender_id' => $this->id,
                        'from'      => $this->id,
                        'to'        => 'dispatcher',
                        'action'    => 'worker_kill',
                        'params'    => [
                            'index' => $index,
                            'ttl'   => strtotime("+".self::CHILDREN_KILL_TTL." seconds"),
                            'try'   => 1,
                        ],
                    ];
                    $msg = new SimpleMessage($args);
                    $this->queue->publish($msg, $this->channel, 'dispatch');
                }
                if ($this->children[$index]['status']!=self::FINISHED)
                    $this->children[$index]['status']==self::FAILED;
            }
            $this->killed = true;
            $this->status = self::FAILED;
            $this->sendStatus('job_failed', 'control');
            $this->persist();
            $lock->unlock();
        }
    }
    
    protected function startChildren()
    {
        if ($this->killed) return;
        $lock = new \swoole_lock(SWOOLE_MUTEX);
        $lock->lock();
        foreach(array_keys($this->children) as $index) {
            if (($this->children[$index]['status']==self::PENDING) && ($this->countRunningChildren()<$this->maxRunningChildren)) {
                $this->children[$index]['status']=self::RUNNING;
                switch ($this->children[$index]['type']) {
                    case 'job':
                        $msg = new SimpleMessage([
                            'sender_id' => $this->id,
                            'from'      => $this->id,
                            'to'        => $this->children[$index]['data']['job_id'],
                            'action'    => 'start',
                            'params'    => $this->args,
                        ]);
                        $this->queue->publish($msg, $this->channel, 'control');
                        //$this->children[$index]['data']->start();
                        break;
                    case 'task':
                        $this->dispatch($this->children[$index]);
                        break;
                    default:
                        throw new \Exception("Tipo de tarea desconocida ".$this->children[$index]['type']);
                }
            }
        }
        $lock->unlock();
    }
        
    public function countRunningChildren() : Int
    {
        $running = 0;
        foreach($this->children as $children) {
            if ($children['status']==self::RUNNING) {
                $running++;
            }
        }
        return $running;
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    protected function dispatch($task)
    {
        $args = [
            'sender_id' => $this->id,
            'from'      => $this->id,
            'to'        => 'dispatcher',
            'action'    => 'dispatch',
            'data'      => $task,
        ];
        $msg = new SimpleMessage($args);
        $this->queue->publish($msg, $this->channel, 'dispatch');
    }
    
    public function children_failed($msg)
    {
        // *todo: si no quedan reintentos, el trabajo principal falla, ¿qué hacemos con el resto de hijos?
        $data = (object) $msg['data'];
        if ($data->type=="task") {
            $this->children[$data->index]['status'] = $data->status;
            $this->children[$data->index]['result'] = $data->result;
        } else {
            // *todo: solución más elegante
            foreach ($this->children as &$child) {
                if ($child['data']['job_id']==$msg['from']) {
                    $child['status'] = self::FAILED;
                }
            }
        }
        if (DEBUG) echo CLI::colorStr("ha fallado un worker!!", "yellow", "default").PHP_EOL;
        if ($this->children[$data->index]['try']<$this->maxRetries) {
            $this->children[$data->index]['alive'] = 0;
            $this->corpses[] = $this->children[$data->index];
            $this->children[$data->index]['status'] = self::PENDING;
            $this->children[$data->index]['result'] = null;
            $this->children[$data->index]['alive'] = 1;
            $this->children[$data->index]['try']=$this->children[$data->index]['try']+1;
            $this->startChildren();
        } else {
            if (DEBUG) echo CLI::colorStr("ha fallado un JOB!!", "white", "red").PHP_EOL; 
            $this->status = $this->checkStatus();
        }
        
        $this->persist();
        
    }
    
    public function children_finish($msg)
    {
        $data = (object) $msg['data'];
        if ($data->type=="task") {
            $this->children[$data->index]['status'] = $data->status;
            $this->children[$data->index]['result'] = $data->result;
        } else {
            // *todo: solución más elegante
            foreach ($this->children as &$child) {
                if ($child['data']['job_id']==$msg['from']) {
                    $child['status'] = self::FINISHED;
                }
            }
        }
          
        $this->status = $this->checkStatus();
        
        //echo "Estado de $this->id: ".self::STATUS[$this->status]." ($this->status)".PHP_EOL;
        // si parent es null, lo envío a la cola externa
        if (!is_null($this->parent)) {
            $this->sendStatus('children_finish', 'control', $data->index);
        } else {
            $this->sendStatus('children_finish', 'control', $data->index);
        }
        if ($this->status==self::FINISHED) {
            $this->job_finish($msg);
        } elseif ($this->status==self::FAILED) {
            $this->job_failed($msg);
        } else {
            $this->startChildren();
        }
        $this->persist();
        return true;
    }
    
    public function job_finish($msg)
    {
        $this->sendStatus('job_finish', 'control');
    }
    
    public function job_failed($msg)
    {
        $this->sendStatus('job_failed', 'control');
    }
    
    public function taskCount()
    {
        return count($this->children);
    }
    
    public function addPartial($request)
    {
        $index = $request['data']['index'];
        $this->children[$index]['status'] = self::PENDING;
        $this->children[$index]['waiting_for_trigger'] = false;
        $this->children[$index]['data']['result'] = $request['data']['result'];
        $this->status = self::RUNNING;
        $this->startChildren();
    }
    
    protected function checkStatus() // REVISAR (REINTENTOS)
    { 
        $lock = new \swoole_lock(SWOOLE_MUTEX);
        $lock->lock();
        $statusCount = [
            self::WAITING  => 0,
            self::PENDING  => 0,
            self::RUNNING  => 0,
            self::FINISHED => 0,
            self::FAILED   => 0,
        ];
        foreach ($this->children as $index=>$child) {
            if ($index!=="") {
                if ($child['status']==self::FAILED && $child['try']<$this->maxRetries && !$this->killed) {
                    $statusCount[self::RUNNING]++; // al worker le quedan reintentos, por lo que el job está en ejecución 
                } else {
                    $statusCount[$child['status']]++;
                }
            }
        }
        if ($statusCount[self::WAITING]>0) $status = self::WAITING;
        if ($statusCount[self::PENDING]>0) $status = self::RUNNING;
        if ($statusCount[self::RUNNING]>0) $status = self::RUNNING;
        if ($statusCount[self::FAILED]>0)  $status = self::FAILED;
        if (!isset($status)) $status = self::FINISHED;
        
        $lock->unlock();
        return $status;
    }
    
}