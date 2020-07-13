<?php
namespace model\web\Jobs\App\Jobs;

class Config
{
    const SERVICES = [
        'main'          => JOBS_NAMESPACE.'Config\\MainConfig',
        'queue'         => JOBS_NAMESPACE.'Config\\RabbitQueueConfig',
        'log'           => JOBS_NAMESPACE.'Config\\LoggerQueueConfig',
        'runnable'      => JOBS_NAMESPACE.'Config\\RunnableConfig',
    ];
    
    public static function get($domain, $value=null)
    {
        if (array_key_exists($domain, self::SERVICES)) {
            $service = self::SERVICES[$domain];
            return $service::get($value);
        } else {
            return null;
        }
    }
}

