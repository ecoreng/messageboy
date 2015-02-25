<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;
use \PhpAmqpLib\Connection\AMQPConnection as Connection;
use \PhpAmqpLib\Message\AMQPMessage;

/**
 * AMPQ Producer Adapter
 * 
 * http://www.rabbitmq.com/tutorials/amqp-concepts.html
 */
class AMPQAdapter implements \ecoreng\MessageBoy\Adapter
{

    protected $connection;
    protected $channel;

    public function __construct(Connection $connection = null)
    {
        if ($connection === null) {
            // default credentials on a localhost
            $this->connection = new Connection('localhost', 5672, 'guest', 'guest');
        } else {
            $this->connection = $connection;
        }
        $this->channel = $this->connection->channel();
    }

    public function handle(Message $message)
    {
        // subject equals routing key
        // params equals headers
        // destinataries equal queues ?
        
        // make this configurable
        $exchange = '';
        
        // make these configurable
        $properties = array('content_type' => 'text/plain', 'delivery_mode' => 2);
        
        
        // exchanges in RabbitAMPQ
        // (default) -> automatic direct routing (key = queue name)
        // amq.direct -> routing key is used to pick a queue
        // amq.fanout -> ignores routing key and delivers to all
        // amq.topic -> messages are routed depending on routing key and pattern binding?
        // amq.match (amq.headers in rabbitmq) -> uses message headers to route instead of the key
        
        // or any custom exchange
        
        // create a queue
        // $this->channel->queue_declare($to, false, false, false, false);
        
        
        foreach ($message->getTo() as $routingKey) {
            $msg = new AMQPMessage((string) $message->getBody(), $properties);
            
            // check batch publishing
            // https://github.com/videlalvaro/php-amqplib#batch-publishing
            $this->channel->basic_publish($msg, $exchange, $routingKey);
        }
        
        $this->channel->close();
        $this->connection->close();
    }

}
