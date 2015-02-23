<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

class ClosureAdapter implements \ecoreng\MessageBoy\Adapter
{
    protected $handler;

    public function __construct(\Closure $handler)
    {
        $this->handler = $handler;
    }

    public function handle(Message $message)
    {
        $handler = $this->handler;
        return $handler($message);
    }
}
