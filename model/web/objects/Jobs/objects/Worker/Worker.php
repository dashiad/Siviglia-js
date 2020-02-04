<?php
namespace model\web\Jobs;

use \lib\model\{BaseModel, BaseException};

class WorkerException extends BaseException
{
    const ERR_WORKER_NOT_FOUND    = 1;
    const ERR_LOGIN_REQUIRED      = 2;
    const ERR_NOT_ALLOWED         = 3;
    const ERR_INVALID_PARAMETERS  = 4;
}

class Worker extends BaseModel
{
    public const WAITING  = 0;
    public const PENDING  = 1;
    public const RUNNING  = 2;
    public const FINISHED = 3;
    public const FAILED   = 4;
    
    public const STATUS = [
        self::WAITING  => 'waiting',
        self::PENDING  => 'pending',
        self::RUNNING  => 'running',
        self::FINISHED => 'finished',
        self::FAILED   => 'failed',
    ];
    
    public static function getValidStatusKeys() : Array
    {
        return array_keys(self::STATUS);
    }
    
    public static function getStatus(Int $status) : ?String
    {
        return self::STATUS[$status] ?? null;
    }
}
