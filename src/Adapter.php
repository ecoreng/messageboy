<?php

namespace ecoreng\MessageBoy;

use \ecoreng\MessageBoy\Message;

interface Adapter
{
    /**
     * Handle the message
     *
     * @param Message $message
     */
    public function handle(Message $message);
}
