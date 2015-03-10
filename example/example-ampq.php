<?php
include('../vendor/autoload.php');

use MessageBoy\MessageDispatcher;
use MessageBoy\SimpleMessage;
use MessageBoy\Adapters\AMPQAdapter;
use PhpAmqpLib\Connection\AMQPConnection as Connection;

$md = new MessageDispatcher;

// (Register example adapters)
$ampq = new Connection('dev.rabbitmq.com', 5672, 'guest', 'guest');
$md->registerAdapter(new AMPQAdapter($ampq), 'ampq');

$message = (new SimpleMessage)
    ->setBodyString('Long Message Body aaaa eeee iiii ooo uuu')
    ->setSubject('Short Subject')
    ->setToArray(['test@example.com'])
    ->setFrom('me@me.com');

$md->dispatch($message);
