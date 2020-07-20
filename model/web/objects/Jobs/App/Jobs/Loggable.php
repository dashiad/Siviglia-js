<?php
namespace model\web\Jobs\App\Jobs;

trait Loggable
{
    protected $log;
    
    public function logInitialize()
    {
        //
    }
    
    public function log($level, $msg)
    {
        //$simpleMessage = new SimpleMessage(json_decode($msg, true));
        //$this->log->publish($simpleMessage, $level);
    }
    
}

