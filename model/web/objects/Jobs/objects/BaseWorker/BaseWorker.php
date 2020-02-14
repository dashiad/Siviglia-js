<?php
namespace model\web\Jobs;

use lib\model\BaseException;
use model\web\Jobs\App\Jobs\Workers\Worker;

class BaseWorkerException extends BaseException
{
    // TODO: definir y lanzar errores 
    const ERR_TEST = 1;
    const TXT_TEST = "Error";
}

abstract class BaseWorker extends Worker
{
    protected static $defaultName = 'generic_worker';
    protected $name;
    protected $definition;
    
    public function __construct($args)
    {
        $this->definition = self::loadDefinition();
        parent::__construct($args);
    }
        
    public static function loadDefinition()
    {
        $reflector = new \ReflectionClass(static::class);
        if ($reflector->isSubclassOf("model\web\Jobs\BaseWorker")) {
            $className = $reflector->getName()."\\Definition";
            $definition = new $className;
        } else {
            $definition = new BaseWorkerDefinition;
        }
        $definition->name = static::$defaultName;
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