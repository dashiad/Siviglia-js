<?php
namespace model\web\Jobs\workers;

use model\web\Jobs\BaseWorker;
use model\web\Jobs\App\Jobs\Queue;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;

class JobTimeout extends BaseWorker
{
    protected static $defaultName = 'job_timeout_worker';
    protected $conn;
    
    protected function init()
    {
        $this->conn = Queue::connect($this->id);
    }
    
    protected function runItem($item)
    {
        $expiredJobs = [];
        $ttl = $item['ttl'] ?? null;
        if (!empty($ttl)) {
            $expirationDate = 0; // calcular
            if ($ttl < $expirationDate) {
                $args = [
                    'from'   => $this->id,
                    'to'     => $item['job_id'],
                    'action' => "kill",
                ];
                $msg = new SimpleMessage($args);
                $this->conn->publish($msg, $this->conn->getDefaultChannel(), 'control');
                $expiredJobs[] = $item['job_id'];
            }
        }
        return $expiredJobs;
    }
}