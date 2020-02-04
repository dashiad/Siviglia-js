<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\Runnables\Job;
use model\web\Jobs\App\Jobs\Interfaces\StatusInterface;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;

class JobManager implements StatusInterface
{
    protected $jobs  = [];
    protected $triggers = [];
    
    const ACTIONS = [
        'create',
        'start',
        'children_finish',
        'children_failed',
        'job_finish',
        'status',
    ];
    
    /**
     * Constructor for JobManager
     **/
    public function __construct()
    {
        $this->id = Config::get('main', 'app');
        $this->queue = Queue::connect($this->id);
        $this->channel = $this->queue->getDefaultChannel();
        $this->queue->createQueue($this->id, $this->channel, false);
        $this->queue->subscribe('control', $this->channel, $this->id);
        //$this->queue->subscribe('main', $this->channel, $this->id);
        $this->jobs = [];
    }
    
    /**
     * Inizialize queue
     **/    
    public function init()
    {
        $this->queue->listen($this, $this->channel);
    }
    
    /**
     * 
     * Returns JobManager id
     * 
     * @return String|NULL
     **/
    public function getId() : ?String
    {
        return $this->id;
    }
    
    /**
     * 
     * 
     * @param $msg
     */
    public function handle($msg)
    {
        $request=$this->queue->extractMessage($msg, true);
        if (in_array($request["action"], self::ACTIONS)) {
            $this->{$request["action"]}($request);
        }
    }
    
    /**
     * 
     * @param unknown $trigger
     * @param unknown $jobDescription
     */
    protected function addTrigger($trigger, $jobDescription)
    {
        $id = $jobDescription['job_id'] ?? uniqid($jobDescription['name']."_");
        $this->triggers[$trigger][$id]['description'] = $jobDescription['job'];
        $this->triggers[$trigger][$id]['run_on_partials'] = $jobDescription['run_on_partials'] ?? false; // por defecto solo funciona con jobs completos
        $this->triggers[$trigger][$id]['launched_for'] = [];
    }
    
    /**
     * 
     * @param unknown $trigger
     * @param unknown $id
     */
    protected function removeTrigger($trigger, $id)
    {
        if(isset($this->triggers[$trigger][$id])) {
            unset($this->triggers[$trigger][$id]);
        }
    }
    
    /**
     * 
     * @param unknown $request
     */
    protected function create($request)
    {
        if  (isset($request['data']['on'])) {
            $this->addTrigger($request['data']['on'], $request['data']);
        } else {
            $job = new Job($this->queue, $request['data']);
            $this->jobs[$job->getId()] = $job;
            if (DEBUG) echo "Created ".$job->getId().PHP_EOL;
        }
    }
    
    /**
     * 
     * @param unknown $request
     */
    protected function start($request)
    {
        if ($this->existsJob($request['to'])) {
            $job = $this->jobs[$request['to']];
            if ($job->getStatus()==self::WAITING) {
                $job ->start();
                if (DEBUG) echo "Started ".$job->getId().PHP_EOL;
            }
        }
    }

    /**
     * 
     * @param unknown $request
     */
    protected function children_failed($request)
    {
        if ($this->existsJob($request['to'])) {
            if (DEBUG) echo "failed ".$request['from'].PHP_EOL;
            $job = $this->jobs[$request['to']];
            $job->children_failed($request);
        }
    }
    
    /**
     * 
     * @param unknown $request
     */
    protected function children_finish($request)
    {
        if ($this->existsJob($request['to'])) {
            if (DEBUG) echo "finished ".$request['from'].PHP_EOL;
            $job = $this->jobs[$request['to']];
            $job->children_finish($request);
            $this->trigger($job->getName(), $request);
        }
    }
    
    /**
     * 
     * @param unknown $request
     */
    protected function job_finish($request)
    {
        if ($this->existsJob($request['from'])) {
            echo "finished ".$request['from'].PHP_EOL;
            $this->trigger($request['name'], $request);
        }
    }
     
    /**
     * 
     * @param unknown $trigger
     */
    protected function trigger($trigger, $request)
    {
        if (array_key_exists($trigger, $this->triggers)) {          
            foreach ($this->triggers[$trigger] as &$hook) {
                if ($request['action']=='children_finish' && $hook['run_on_partials']) {
                    $creator = $request['to'];
                    $job = $this->launchTrigger($hook, $creator);
                    $job->addPartial($request);
                    //$job->start();
                }
                if ($request['action']=='job_finish' && !$hook['run_on_partials']) {
                    $creator = $request['from'];
                    $job = $this->launchTrigger($hook, $creator);
                    $job->start();
                }
            }
        }
    }
    
    /**
     * 
     * @param Array $hook
     * @param String $creator
     * @return \Jobs\Runnables\Job
     */
    protected function launchTrigger(Array &$hook, String $creator)
    {
        if (!array_key_exists($creator, $hook['launched_for'])) {
            $job = new Job($this->queue, $hook['description']);
            $job->addEmptyChildren(count($this->jobs[$creator]->getChildren()));
            $this->jobs[$job->getId()] = $job;
            $hook['launched_for'][$creator] = $job;
        } else {
            $job = $hook['launched_for'][$creator];
        }
        return $job;
    }
    
    /**
     * 
     * @param unknown $request
     */
    protected function status($request)
    {
        $jobs_status = [];
        foreach ($this->jobs as $job) {
            $jobs_status[$job->getId()] = [
                'status'   => self::STATUS[$job->getStatus()],
                'children' => $job->getChildren(),
            ];
        }
        $args = [
            'from' => 'job_manager',
            'to'   => null,
            'data' => $jobs_status,
        ];
        $msg = new SimpleMessage($args);
        $this->queue->publish($msg, $this->channel, 'control');
    }
    
    /**
     * 
     * @param String $id
     * @return boolean
     */
    protected function existsJob(?String $id)
    {
        if (!empty($id)) {
            return array_key_exists($id, $this->jobs);
        } else {
            return false;
        }
    }
    
    public static function createJob(Array $definition)
    {
        $queue = Queue::connect('creator');
        $definition['job_id'] = uniqid($definition['name'].'_');
        $msg = new SimpleMessage([
            'from'   => 'application',
            'to'     => Config::get('main', 'app'),
            'action' => "create",
            'data'   => $definition,
        ]);
        $queue->publish($msg, $queue->getDefaultChannel(), 'control');
        $msg = new SimpleMessage([
            'from'   => 'application',
            'to'     => $definition['job_id'],
            'action' => "start",
        ]);
        $queue->publish($msg, $queue->getDefaultChannel(), 'control', $definition['job_id']);
    }
    
}


