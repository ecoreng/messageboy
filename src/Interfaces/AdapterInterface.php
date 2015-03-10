<?php

namespace MessageBoy\Interfaces;

interface AdapterInterface
{
    /**
     * Handle the message
     *
     * @param Message $message
     */
    public function handle(MessageInterface $message);
}
