<?php

namespace MessageBoy;

use \MessageBoy\Interfaces\MessageInterface;
use \MessageBoy\Interfaces\StreamableInterface;
use \Iterator;

class Message implements MessageInterface
{
    /**
     * Streamable body of the message
     *
     * @var Stream
     */
    protected $body;

    /**
     * Iterator that returns recipients
     *
     * @var Iterator
     */
    protected $to;

    /**
     * Remitent
     *
     * @var string
     */
    protected $from;

    /**
     * Short sentence describing the what's the message about
     *
     * @var string
     */
    protected $subject;

    /**
     * Parameter Array
     *
     * @var array
     */
    protected $params = [];

    /**
     * Returns the streamable body of the message
     *
     * @return Stream
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the Iterator for the recipients
     *
     * @return Iterator
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Return the remitent
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Return the subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the Stream object for the body
     *
     * @param Stream $body
     * @return \ecoreng\MessageBoy\Concrete\Message
     */
    public function setBody(StreamableInterface $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set the Iterator object for the recipients
     *
     * @param Iterator $to
     * @return \ecoreng\MessageBoy\Concrete\Message
     */
    public function setTo(Iterator $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Set the remitent
     *
     * @param string $from
     * @return \ecoreng\MessageBoy\Concrete\Message
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Set the subject
     *
     * @param string $subject
     * @return \ecoreng\MessageBoy\Concrete\Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the parameter array
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = array_merge_recursive($this->params, $params);
        return $this;
    }
}
