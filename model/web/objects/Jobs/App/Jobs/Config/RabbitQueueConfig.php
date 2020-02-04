<?php
namespace model\web\Jobs\App\Jobs\Config;

class RabbitQueueConfig extends BaseConfig
{
    const CONFIG = [
        'class'              => JOBS_NAMESPACE.'QueueManagers\\RabbitQueueManager',
	//'host'    => 'dove.rmq.cloudamqp.com',
	'host' => 'localhost',
        //'user'    => 'mfchcruf',
        'user' => 'jobs',
        //'pass'    => 'wKabTHov8l6L0sftLqLU8Ln4ATKbBwh5',
        'pass' => 'jobs',
        'port'    => 5672,
        //'vhost'   => 'mfchcruf',
        'vhost' => 'jobs',
	'control'            => 'control',
        'main'               => 'main',
        'connection_timeout' => 1,
    ];
}
