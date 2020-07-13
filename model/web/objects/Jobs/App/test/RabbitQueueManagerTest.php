<?php
declare(strict_types=1);
require_once(__DIR__.'/../bin/bootstrap.php');

use PHPUnit\Framework\TestCase;
use model\web\Jobs\App\Jobs\QueueManagers\RabbitQueueManager;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;

//
/**
 * RabbitQueueManager test case.
 */

class RabbitQueueManagerTest extends TestCase
{

    /**
     *
     * @var RabbitQueueManager
     */
    private $rabbitQueueManager;

    protected function setUp() : void
    {
        parent::setUp();
        $this->rabbitQueueManager = new RabbitQueueManager();
    }

    protected function tearDown() : void
    {
        $this->rabbitQueueManager = null;
        parent::tearDown();
    }
    
    public function testConnect()
    {
        $this->rabbitQueueManager->connect();
        $className = get_class($this->rabbitQueueManager->getChannel($this->rabbitQueueManager->getDefaultChannel()));
        $this->assertEquals('PhpAmqpLib\\Channel\\AMQPChannel', $className);
        $channel = $this->rabbitQueueManager->addChannel();
        $this->assertEquals(2, $channel);
        $this->rabbitQueueManager->deleteChannel($channel);
    }
    
    public function testPublish()
    {
        $this->rabbitQueueManager->createQueue('test', 1, true);
        $this->rabbitQueueManager->subscribe('control', 1, 'test');
        $msg = new SimpleMessage(['from'=>'test', 'to'=>'test', 'data'=>'hola']);
        $this->rabbitQueueManager->publish($msg, 1, 'control');
        $this->addToAssertionCount(1);
    }

    public function testConsume()
    {
        /*$process = new \Swoole\Process( function () { 
            $this->rabbitQueueManager->listen(new TestHelper(), 1);
        });
        $process->start();
        $process->wait(false);
        $msg = new SimpleMessage(['from'=>'test', 'to'=>'test', 'data'=>'hola']);
        $this->rabbitQueueManager->publish($msg, 1, 'control');*/
        $this->assertTrue(true);
    }
    
    public function testCreateJob()
    {
        $job_id = uniqid('test_');
        $args = [
            'type' => 'job',
            'name' => 'test_job',
            'job_id' => $job_id,
            'task' => [
                'type' => 'task',
                'name' => 'test_task',
                'args' => [
                    'task'   => 'Test',
                    'type'   => 'DateRange',
                    'params' => [
                        'start_date'     => '2019-11-01 00:00:00',
                        'end_date'       => '2019-11-30 23:59:59',
                        'max_chunk_size' => 10,
                    ],
                ],
            ]
        ];
        $msg = new SimpleMessage([
            'from'   => 'test',
            'to'     => 'smartclip_jobs',
            'action' => 'create',
            'data'   => $args,
        ]);
        $this->rabbitQueueManager->publish($msg, 1, 'control');
        $msg = new SimpleMessage([
            'from'   => 'test',
            'to'     => $job_id,
            'action' => 'start',
        ]);
        $this->rabbitQueueManager->publish($msg, 1, 'control');
        
        $this->assertTrue(true);
    }
}

class TestHelper
{
    public function getId()
    {
        return 'test';
    }
    
    public function handle($msg)
    {
        echo($msg->body);
        return false;
    }
}