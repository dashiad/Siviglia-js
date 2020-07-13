<?php
namespace model\web\Jobs\App\Jobs\QueueManagers;

use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;
use model\web\Jobs\App\Jobs\Messages\BaseMessage;
use model\web\Jobs\App\Jobs\CLI;

class RabbitQueueManager extends AbstractQueueManager
{   
    
    protected static $type = 'queue';
    protected static $maxChannels = 65535;
        
    public function connect()
    {
        try {
        $this->connection = new AMQPStreamConnection(
            static::$config['host'],
            static::$config['port'],
            static::$config['user'],
            static::$config['pass'],
            static::$config['vhost']);
        $this->channels[1] = $this->connection->channel();
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }
    
    
    public function createQueue(String $id, Int $channel, Bool $autodelete=true)
    {
        $this->autodelete = $autodelete;
        $queue = $this->connection->channel($channel)->queue_declare($id, false, false, false, $autodelete, false);
    }
    
    public function deleteQueue(String $id, Int $channel)
    {
        // *todo: borrar colas
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::disconnect()
     */
    public function disconnect()
    {
        $this->connection->close();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::subscribe()
     */
    public function subscribe($subscriptions=[], Int $channel, String $queue, String $routingKey='')
    {
        foreach ((array)$subscriptions as $subscription) {
            //if(!in_array($subscription, $this->subscriptions)) {
                $this->connection->channel($channel)->queue_bind($queue, $subscription, $routingKey);
                $this->subscriptions[] = $subscription;
            //}
        }
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::publish()
     */
    public function publish(BaseMessage $msg, Int $channel, String $queue='', String $key='')
    {
        $rabbitMsg = new AMQPMessage($msg->toJson(), ['content_type' => 'text/plain',
            'delivery_mode' => 2]);
        try {
            $this->connection->channel($channel)->basic_publish($rabbitMsg, $queue, $key);
        } catch (\Exception $e) {
            echo CLI::colorStr("ERROR ENVIANDO MENSAJE".PHP_EOL, 'white'. 'red');            
        }
    }
    
  /**
   * 
   * {@inheritDoc}
   * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::listen()
   */
    public function listen($listener, Int $channel, $id=null)
    {
        
        /**
         * basic_consume(
         *     @param string $queue
         *     @param string $consumer_tag
         *     @param bool $no_local
         *     @param bool $no_ack
         *     @param bool $exclusive
         *     @param bool $nowait
         *     @param callable|null $callback
         *     @param int|null $ticket
         *     @param array $arguments
         * )
         */
        
        $channel = $this->connection->channel($channel);
        $id = $id ?? $listener->getId();
        $channel->basic_consume($id, $listener->getId(), false, true, false, false, [$listener, 'handle']);
        while ($channel->is_consuming()) {
            try {
                $channel->wait();
            } catch (\Exception $e) {
                if (DEBUG) echo CLI::colorStr($e->getMessage().PHP_EOL, 'white'. 'red');
                throw $e;
            }
        }
        echo "dejo de consumir ".$listener->getId()." por petición de ".getmypid()." ".get_class($listener).PHP_EOL;
    }
    
    public function getDefaultChannel() : Int
    {
        return 1;
    }
    
    public function createChannel() : Int
    {
        try {
            $channelId = $this->connection->get_free_channel_id();
            $this->channels[$channelId] = $this->connection->channel($channelId);
        } catch (\Exception $e) {
            echo $e->msg;
            $channelId = null;
        } finally {
            return $channelId;
        }
    }

    /**
     * 
     * {@inheritDoc}
     * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::deleteChannel()
     */
    public function deleteChannel(Int $index)
    {
        $this->channels[$index]->close();
        unset($this->channels[$index]);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::stopListening()
     */
    public function stopListening($listener, Int $channel)
    {
        $this->connection->channel($channel)->basic_cancel($listener->getId(), false);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \model\web\Jobs\App\Jobs\QueueManagers\AbstractQueueManager::extractMessage()
     */
    public function extractMessage($msg, $associative=false) : Array
    {
        return json_decode($msg->body, $associative);
    }
    
}
