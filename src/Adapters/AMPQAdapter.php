<?php

namespace MessageBoy\Adapters;

use \MessageBoy\Interfaces\MessageInterface;
use \MessageBoy\Interfaces\AdapterInterface;

use \PhpAmqpLib\Connection\AMQPConnection as Connection;
use \PhpAmqpLib\Message\AMQPMessage;

/**
 * AMPQ Producer Adapter
 *
 * http://www.rabbitmq.com/tutorials/amqp-concepts.html
 */
class AMPQAdapter implements AdapterInterface
{
    protected $connection;
    protected $channel;
    protected $exchange;
    protected $createQueues;

    public function __construct(Connection $connection = null, $exchange = '', $createQueues = false)
    {
        if ($connection === null) {
            // default credentials on localhost
            $this->connection = new Connection('localhost', 5672, 'guest', 'guest');
        } else {
            $this->connection = $connection;
        }
        $this->channel = $this->connection->channel();
        $this->exchange = $exchange;
        $this->createQueues = $createQueues;
    }

    public function handle(MessageInterface $message)
    {
        $params = $message->getParams();

        $contentType = 'text/plain';
        if (isset($params['ampq.content-type'])) {
            $contentType = $params['ampq.content-type'];
        }

        $deliveryMode = 2;
        if (isset($params['ampq.delivery-mode'])) {
            $deliveryMode = $params['ampq.delivery-mode'];
        }
        $properties = ['content_type' => $contentType, 'delivery_mode' => $deliveryMode];

        // subject equals routing key
        // params equals headers
        // destinataries equal queues

        // exchanges in RabbitAMPQ
        // (default) -> automatic direct routing (key = queue name)
        // amq.direct -> routing key is used to pick a queue
        // amq.fanout -> ignores routing key and delivers to all
        // amq.topic -> messages are routed depending on routing key and pattern binding?
        // amq.match (amq.headers in rabbitmq) -> uses message headers to route instead of the key
        //
        // or any custom exchange

        $exchange = $this->exchange;
        if (isset($params['ampq.exchange'])) {
            $deliveryMode = $params['ampq.exchange'];
        }

        foreach ($message->getTo() as $routingKey) {
            $msg = new AMQPMessage((string) $message->getBody(), $properties);

            if ($this->createQueues) {
                $this->channel->queue_declare($routingKey, false, false, false, false);
            }

            $this->channel->batch_basic_publish($msg, $exchange, $routingKey);
        }
        $this->channel->publish_batch();

        $this->channel->close();
        $this->connection->close();
    }
}
