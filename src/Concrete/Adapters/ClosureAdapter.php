<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

/**
 * Adapter that accepts a 'Closure'/anonymous function as argument in the 
 * consturctor and executes that 'Closure' upon calling the 'handle' method 
 * passing the 'Message' to it.
 */
class ClosureAdapter implements \ecoreng\MessageBoy\Adapter
{
    protected $handler;

    /**
     * Constructor
     * 
     * @param \Closure $handler
     */
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
