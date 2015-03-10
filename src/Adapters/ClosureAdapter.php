<?php

namespace MessageBoy\Adapters;

use \MessageBoy\Interfaces\MessageInterface;
use \MessageBoy\Interfaces\AdapterInterface;

/**
 * Adapter that accepts a 'Closure'/anonymous function as argument in the
 * consturctor and executes that 'Closure' upon calling the 'handle' method
 * passing the 'Message' to it.
 */
class ClosureAdapter implements AdapterInterface
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

    public function handle(MessageInterface $message)
    {
        $handler = $this->handler;
        return $handler($message);
    }
}
