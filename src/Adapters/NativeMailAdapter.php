<?php

namespace MessageBoy\Adapters;

use \MessageBoy\Interfaces\MessageInterface;
use \MessageBoy\Interfaces\AdapterInterface;

/**
 * Sends an email using the native mail function
 *
 * You can add mail headers by passing an associative array with the
 * 'mail.headers' parameter in the message as 'Header' => 'value'
 * so it's sent as "Header: value"
 *
 * The headers can also be sent through the constructor as well as the line
 * separator to be used.
 *
 * Supports multiple destinataries as the mail function handles it properly in
 * one call.
 */
class NativeMailAdapter implements AdapterInterface
{
    protected $headers = [];
    protected $lineSeparator = "\r\n";

    /**
     * Constructor
     *
     * @param array $headers - Headers to be used with the mail function
     * @param string $lineSeparator - Line separator for the message
     */
    public function __construct(array $headers = [], $lineSeparator = "\r\n")
    {
        $this->headers = $headers;
        $this->lineSeparator = $lineSeparator;
    }

    public function handle(MessageInterface $message)
    {
        $headers = $this->headers;

        $params = $message->getParams();
        if (isset($params['mail.headers'])) {
            $headers = array_merge($headers, $params['mail.headers']);
        }

        $to = $message->getTo()->getArrayCopy();
        $to = implode(', ', $to);
        $to = trim($to, ', ');

        $subject = substr($message->getSubject(), 0, 150);
        $msg = wordwrap((string) $message->getBody(), 70, $this->lineSeparator);

        $from = $message->getFrom();
        $headers['From'] = $from ? $from : null;
        return mail($to, $subject, $msg, $this->generateHeaders($headers));
    }

    protected function generateHeaders(array $headers)
    {
        $headerString = '';
        foreach ($headers as $header => $value) {
            $headerString .= $header . ': ' . $value . $this->lineSeparator;
        }
        return $headerString;
    }
}
