<?php

namespace MessageBoy\Adapters;

use \MessageBoy\Interfaces\MessageInterface;
use \MessageBoy\Interfaces\AdapterInterface;

/**
 * NullAdapter
 *
 * Dummy Adapter that does nothing with the message
 */
class NullAdapter implements AdapterInterface
{

    public function handle(MessageInterface $message)
    {

    }
}
