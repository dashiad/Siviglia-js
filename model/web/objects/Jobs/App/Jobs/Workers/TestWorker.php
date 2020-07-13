<?php
namespace model\web\Jobs\App\Jobs\Workers;

class TestWorker extends Worker
{
    
    protected $name = 'test_worker';
    
    const ONE_FAILURE_OUT_OF = 100;
    
    protected function init()
    {
        //
    }
    
    public function runItem($item)
    {
        if ( (rand(1, self::ONE_FAILURE_OUT_OF))==1 ) {
            throw new \Exception("error aleatorio");
        } else {
            return $item.PHP_EOL;
        }
    }
    
}