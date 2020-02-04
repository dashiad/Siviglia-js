<?php
namespace model\web\Jobs\App\Jobs\Config;

class LoggerQueueConfig extends BaseConfig
{
    const CONFIG = [
        'class'      => JOBS_NAMESPACE.'QueueManagers\\LoggerQueueManager',
        'levels'     => ['DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR'],
        'default'    => 'INFO',
        'dir'        => '/vagrant/logs',
        'file'       => 'queues.log',
        'format'     => 'c',
    ];
}
