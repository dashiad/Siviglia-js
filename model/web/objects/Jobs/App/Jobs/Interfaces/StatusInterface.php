<?php
namespace model\web\Jobs\App\Jobs\Interfaces;

interface StatusInterface
{
    const WAITING  = 0;
    const PENDING  = 1;
    const RUNNING  = 2;
    const FINISHED = 3;
    const FAILED   = 4;
    
    const STATUS = [
        self::WAITING  => 'waiting',
        self::PENDING  => 'pending',
        self::RUNNING  => 'running',
        self::FINISHED => 'finished',
        self::FAILED   => 'failed',
    ];
}