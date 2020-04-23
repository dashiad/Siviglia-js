<?php
namespace model\web\Jobs\App\Jobs\Config;

class RabbitQueueConfig extends BaseConfig
{
    const CONFIG = [
        'class'              => JOBS_NAMESPACE.'QueueManagers\\RabbitQueueManager',
	'host'               => 'services.net1.hadoop.oraclevcn.com',
        'user'               => 'jobs',
        'pass'               => 'oeb~ie9Eh3keequ7',
        'port'               => 5672,
        'vhost'              => 'jobs',
	'control'            => 'control',
        'dispatch'           => 'dispatch',
        'connection_timeout' => 1,
    ];
}
