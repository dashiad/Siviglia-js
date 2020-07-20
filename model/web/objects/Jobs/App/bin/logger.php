<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\Queue;

class Log {
    
    const FILENAME = __DIR__ . '/../log/jobs_queue.log';
    
    protected $file;
    
    public function __construct()
    {
        $this->id = 'logger';
        $this->file = fopen(self::FILENAME, "a");
        $this->queue = Queue::connect('logger');
        $this->queue->createQueue('logger', $this->queue->getDefaultChannel());
        $this->queue->subscribe('control', $this->queue->getDefaultChannel(), 'logger');
        $this->queue->subscribe('dispatch', $this->queue->getDefaultChannel(), 'logger');
        $this->queue->listen($this, $this->queue->getDefaultChannel(), 'logger');
        echo "Logger started".PHP_EOL;
    }
    
    public function getId()
    {
        return 'logger';
    }
    
    public function handle($msg)
    {
        fwrite($this->file, json_encode($msg).PHP_EOL);
    }
    
    public function __destruct()
    {
        fclose($this->file);
    }
    
}

new Log();


