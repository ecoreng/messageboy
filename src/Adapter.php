<?php

namespace ecoreng\MessageBoy;

use \ecoreng\MessageBoy\Message;

interface Adapter
{

    public function handle(Message $message);
}
