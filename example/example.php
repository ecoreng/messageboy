<?php
include('../vendor/autoload.php');

use MessageBoy\MessageDispatcher;
use MessageBoy\SimpleMessage;
use MessageBoy\Adapters\FileAdapter;

$md = new MessageDispatcher;

// (Register example adapters)
$md->registerAdapter(new FileAdapter('log', null, true, \FILE_APPEND), 'mail');

$message = (new SimpleMessage)
    ->setBodyString('Long Message Body aaaa eeee iiii ooo uuu')
    ->setSubject('Short Subject')
    ->setToArray(['test@example.com'])
    ->setFrom('me@me.com');

$md->dispatch($message);
