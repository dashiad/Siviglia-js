<?php

namespace model\web\Jobs\App\Jobs\Runnables;

use model\web\Jobs\App\Jobs\Interfaces\StatusInterface;

class Task implements StatusInterface
{
    protected $data = [
        'parent'   => null,
        'type'     => null,
        'params'   => [],
        'items'    => [],
        'index'    => 0,
        'number_of_parts' => null,
        'try'      => 0,
        'waiting_for_trigger' => false,
    ];
    
    public function __construct(?Array $args=[])
    {
        foreach ($args as $key=>$value) {
            $this->$key=$value;
        }
    }
   
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function __get($name)
    {
        return $this->data[$name];
    }
    
    public function toJson()
    {
        return json_encode($this->data);
    }
    
    public function toArray() {
        return $this->data;
    }

}