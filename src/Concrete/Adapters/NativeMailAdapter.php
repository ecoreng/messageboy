<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

class NativeMailAdapter implements \ecoreng\MessageBoy\Adapter
{
    protected $headers = [];
    protected $lineSeparator = '';

    public function __construct($headers = [], $lineSeparator = '\r\n')
    {
        $this->headers = $headers;
        $this->lineSeparator = $lineSeparator;
    }

    public function handle(Message $message)
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
            $headerString .= $header . ': ' . $value . $this->lineSeparator . PHP_EOL;
        }
        return $headerString;
    }
}
