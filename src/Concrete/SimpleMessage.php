<?php

namespace ecoreng\MessageBoy\Concrete;

use \ecoreng\MessageBoy\Concrete\Stream;

class SimpleMessage extends Message
{

    /**
     * Creates a Stream object out of the $body string and sets it as the body
     * of the message
     *
     * @param string $body
     * @return \ecoreng\MessageBoy\Concrete\SimpleMessage
     */
    public function setBodyString($body)
    {
        $stream = new Stream('php://memory', 'wb+');
        $stream->write($body);
        $this->setBody($stream);
        return $this;
    }

    /**
     * Creates an \ArrayIterator out of the $to array and sets it as the
     * destinatary of the message
     *
     * @param array $to
     * @return \ecoreng\MessageBoy\Concrete\SimpleMessage
     */
    public function setToArray(array $to)
    {
        $iterator = new \ArrayIterator($to);
        $this->setTo($iterator);
        return $this;
    }
}
