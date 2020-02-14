<?php
namespace model\web\Jobs\App\Jobs\Workers;

use model\web\Jobs\App\Jobs\Queue;
use model\web\Jobs\App\Jobs\Config;
use model\web\Jobs\App\Jobs\Interfaces\StatusInterface;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Persistable;

abstract class Worker implements StatusInterface
{
    
    use Persistable;
    
    protected $id;
    protected $parent;
    protected $queue;
    protected $args;
    protected $standalone = false;
    protected $result = [];
    protected $lastCompletedItem = null;
    protected $items = [];
    protected $totalParts;
    
    protected $name = 'worker';
    protected $index = 0;
    
    protected $modelName = 'model\\web\\Jobs\\Worker';
    protected $fields = [
        'worker_id' => 'id',
        'job_id'    => 'parent',
        'name'      => 'name',
        'status'    => 'status',
        'index'     => 'index',
        'number_of_parts' => 'totalParts',
        'items'     => "items",
        'last_completed_item_index' => 'lastCompletedItem',
        'result'   => 'result',
        'alive'    => 'alive',
        'descriptor' => 'args',
    ];
        
    public function __construct($args)
    {
        $this->args       = array_merge(Config::get('runnable', 'worker'), $args);
        $this->id         = $this->createId();
        $this->parent     = $this->getParent();
        $this->name       = $this->getName();
        $this->index      = $this->args['index'];
        $this->items      = $this->args['items'];
        $this->totalParts = $this->args['number_of_parts'];
        $this->standalone = $this->args['standalone'];
        if (!$this->standalone) {
            $this->queue = Queue::connect($this->id);
        }
        $this->init();
        $this->persist();
    }
    
    
    public function __destruct()
    {
        if (!$this->standalone) {
            $this->queue->disconnect();
        }
    }
    
    public function __get($name)
    {
        if ($name=='items')
            return $this->args['items'];
    }
    
    protected function _run()
    {
        if (empty($this->args['items'])) $this->args['items'] = [];
        foreach ($this->args['items'] as $index=>$item) {
            $this->result[] = $this->runItem($item);
            $this->lastCompletedItem = $index;
            $this->persist();
        }
    }
    
    abstract protected function init();
    abstract protected function runItem($item);  // the real execution

    public function run()
    {
        $this->status=self::RUNNING;
            $this->result = [];
            $this->persist();
            ob_start();
            try {
                $this->_run();
                $this->status=self::FINISHED;
            } catch (\Exception $e) {
                $this->result[] = $e->getMessage();
                $this->status=self::FAILED;
                $this->alive=0;
            } finally {
                $result = ob_get_clean();
                $this->finish($result);
            }
    }
    
    public function getParent() : ?String
    {
        return $this->args['parent'] ?? null;
    }
    
    public function getName() : ?String
    {
        return $this->name ?? null;
    }
    
    protected function createId()
    {
        $name = $this->getName();
        $parent = $this->getParent();
        $prefix = (!is_null($parent)) ? $parent.'.' : '';
        $prefix .= (!is_null($name)) ? $name.'_' : '';
        return uniqid($prefix);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function finish(String $result)
    {
        $action = ($this->status==self::FINISHED) ? 'children_finish' : 'children_failed';
        $args = [
            'from'   => $this->id,
            'to'     => $this->args['parent'],
            'action' => $action,
            'data'   => [
                'type'   => 'task',
                'status' => $this->status,
                'index'  => $this->args['index'],
                'result' => $this->result.$result,
            ],
        ];
        if (!$this->standalone) {
            $msg = new SimpleMessage($args);
            $this->queue->publish($msg, $this->queue->getDefaultChannel(), 'control');
        } else {
            echo json_encode($args).PHP_EOL;
        }
        $this->persist();
    }    
}