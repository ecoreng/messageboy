<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

/**
 * NullAdapter
 *
 * Dummy Adapter that does nothing with the message
 */
class NullAdapter implements \ecoreng\MessageBoy\Adapter
{

    public function handle(Message $message)
    {

    }
}
