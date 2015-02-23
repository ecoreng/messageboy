<?php

namespace ecoreng\MessageBoy\Concrete;

use \ecoreng\MessageBoy\Concrete\Stream;

class SimpleMessage extends Message
{

    public function setBodyString($body)
    {
        $stream = new Stream('php://memory', 'wb+');
        $stream->write($body);
        $this->setBody($stream);
        return $this;
    }

    public function setToArray(array $to)
    {
        $iterator = new \ArrayIterator($to);
        $this->setTo($iterator);
        return $this;
    }
}
