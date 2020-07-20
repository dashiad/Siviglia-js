<?php
namespace model\web\Jobs\App\Jobs\Config;

class MainConfig extends BaseConfig
{
    const CONFIG = [
        'app'             => 'smartclip_jobs',
        'debug'           => true,
        'server_host'     => 'localhost',
        'server_port'     => 8080,
        'dispatcher_host' => 'localhost',
        'dispatcher_port' => 8081,
        'http'            => [
            'worker_num'          => 1,
            'max_request'         => 4,
            'open_http2_protocol' => true,
            'enable_coroutine'    => false,
            'daemonize'           => 0,
            //'pid_file'            => 'var/run/swoole_server.pid'
        ],
    ];
}
