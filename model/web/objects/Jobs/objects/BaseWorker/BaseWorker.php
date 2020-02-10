<?php
namespace model\web\Jobs;

use lib\model\BaseException;

class BaseWorkerException extends BaseException
{
    const ERR_TEST = 1;
    const TXT_TEST = "Error";
}

class BaseWorker 
{
    protected $name;
    protected $definition;
    
    public function __construct()
    {
        $this->definition = $this->loadDefinition();
    }
        
    protected function loadDefinition()
    {
        $reflector = new \ReflectionClass(get_class($this));
        if ($reflector->isSubclassOf("model\web\Jobs\BaseWorker")) {
            $className = $reflector->getName()."\\Definition";
            $definition = new $className;
        } else {
            $definition = new BaseWorkerDefinition;
        }
        $definition->name = $this->name;
        return $definition;
    }
    
    public function getDefinition()
    {
        return $this->definition;
    }
    
    public function getJobDescriptor()
    {
        return json_encode([]);
    }
}