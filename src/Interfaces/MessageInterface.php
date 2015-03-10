<?php

namespace MessageBoy\Interfaces;

use \Iterator;

interface MessageInterface
{

    /**
     * Returns the streamable body of the message
     */
    public function getBody();

    /**
     * Return the Iterator for the recipients
     */
    public function getTo();

    /**
     * Return the remitent
     */
    public function getFrom();

    /**
     * Return the subject
     */
    public function getSubject();

    /**
     * Get parameters
     */
    public function getParams();

    /**
     * Set the Iterator object for the recipients
     *
     * @param Iterator $to
     */
    public function setTo(Iterator $to);

    /**
     * Set the remitent
     *
     * @param string $from
     */
    public function setFrom($from);

    /**
     * Set the subject
     *
     * @param string $subject
     */
    public function setSubject($subject);

    /**
     * Set the Stream object for the body
     *
     * @param Stream $body
     */
    public function setBody(StreamableInterface $body);

    /**
     * Set the parameter array
     *
     * @param array $params
     */
    public function setParams(array $params);
}
