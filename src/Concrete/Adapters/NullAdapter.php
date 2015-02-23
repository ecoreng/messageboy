<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

class NullAdapter implements \ecoreng\MessageBoy\Adapter
{

    public function handle(Message $message)
    {
        
    }
}
