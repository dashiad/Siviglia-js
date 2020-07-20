<?php
namespace model\web\Jobs\App\Jobs\Runnables;

use model\web\Jobs\App\Jobs\Messages\StatusMessage;
use model\web\Jobs\App\Jobs\Queue;
use model\web\Jobs\App\Jobs\Interfaces\StatusInterface;
use model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager;

abstract class AbstractRunnable implements StatusInterface
{    
    // key => callable_method
    const ACTIONS = [
        'poll'            => 'poll',
        'update'          => 'update',
        'halt'            => 'halt',
        'start'           => 'start',
        'success'         => 'success',
        'error'           => 'error',
        'children_finish' => 'children_finish',
        'children_failed' => 'children_failed',
    ];
    const MAX_RUNNING_CHILDREN = 1024;
    
    protected $id;
    protected $name;
    protected $actions = []; // Override in children with custom actions
    protected $prefix = '';
    protected $parent = null;
    protected $children = [];
    protected $args;
    protected $status = self::WAITING;
    protected $queue;
    protected $channel;
    protected $maxRunningChildren;
        
    public function __construct(AbstractQueueManager &$queue, ?Array $args)
    {
        $this->queue = $queue;
        $this->channel = $queue->createChannel();
        $this->parent = (array_key_exists('parent', $args)) ? $args['parent'] :  null;
        $this->name = (array_key_exists('name', $args)) ? $args['name'] : null;
        $this->id = (array_key_exists('job_id', $args)) ? $args['job_id'] :  $this->createId();
        $this->setMaxRunningChildren((array_key_exists('max_running_children', $args)) ? $args['max_running_children'] :  self::MAX_RUNNING_CHILDREN);
        $this->actions = array_merge(self::ACTIONS, $this->actions);
        $this->args = $args;
        $this->initialize();
    }
    
    abstract protected function initialize();
    
    private function setMaxRunningChildren(?Int $maxRunningChildren) 
    {
        if (!empty($maxRunningChildren) && $maxRunningChildren>0) {
            $this->maxRunningChildren = $maxRunningChildren;
        } else {
            $this->maxRunningChildren = 1;
        }
    }
    
    protected function createId()
    {
        $name = (!is_null($this->name)) ? $this->name.'_' : $this->prefix;
        $parent = (!is_null($this->parent)) ? $this->parent.'.' : '';
        $prefix = $parent.$name;
        
        return uniqid($prefix);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getNumberOfChildren() : int
    {
        return count($this->children);
    }

    public function getChildren()
    {
        $children = [];
        foreach($this->children as $child) {
            if (is_subclass_of($child, JOBS_NAMESPACE.'\\Runnables\\AbstractRunnable')) {
                $children[]=$child->getId();
            } else {
                $children[]=$child;
            }
        }
        return $children;
    }
    
    public function getArgs()
    {
        return $this->args;
    }
    
    protected function addAction($key, $callback)
    {
        $this->actions[$key] = $callback;
    }
    
    protected function poll($msg)
    {
        $args= [
            'from'      => $this->getId(),
            'name'      => $this->name,
            'parent'    => $this->parent,
            'children'  => $this->getChildren(),
            'params'    => $this->args,
            'to'        => $msg->from,
            'action'    => 'info',
            'status'    => self::STATUS[$this->status],
        ];
        
        $response = new StatusMessage($args);
        $this->queue->publish($response, 'control');
    }
    
    protected function sendStatus($action, $queue='control', $index=null) 
    {
        if (is_null($index)) {
            $children = $this->children;
        } else {
            $children = [ $index => $this->children[$index] ]; 
        }
        
        $msg = new StatusMessage([
            'from'      => $this->id,
            'to'        => $this->parent,
            'name'      => $this->name,
            'action'    => $action,
            'data'      => "",
            'params'    => $this->args,
            'children'  => $children,
            'children_count' => count($this->children),
            'status'    => $this->status,
            'parent'    => $this->parent,
        ]);
        
        $this->queue->publish($msg, $this->channel, $queue);
    }
    
    public function toArray()
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'status'   => $this->status,
            'params'   => $this->args,
            'children' => $this->children,
        ];
    }
    
    protected function update($msg)
    {
        echo "el proceso $this->id pasa a estado $msg->status".PHP_EOL;
    }
    
    protected function children_finish($msg)
    {
        //
        echo "ha terminado un hijo del trabajo $this->id".PHP_EOL;
    }
        
    public function getQueue()
    {
        return $this->queue;
    }

    public function _handle($msg)
    {
        if ($msg->to==$this->id) {
            if (array_key_exists($msg->action, $this->actions)) {
                return $this->{$this->actions[$msg->action]}($msg);
            } else {
                echo "$this->id descartando action desconocida: $msg->action".PHP_EOL;
            }
        }
    }    
}