<?php
namespace model\web\Jobs\App\Jobs\Config;

class RunnableConfig  extends BaseConfig
{
    const CONFIG = [
        'runnable' => [],
        'job'      => [
            'retries' => 3,
        ],
        'task'     => [],
        'worker'   => [
            'parent'     => null,
            'index'      => 0,
            'standalone' => false,
        ],
    ];
}

