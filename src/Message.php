<?php

namespace ecoreng\MessageBoy;

use \ecoreng\MessageBoy\Concrete\Stream;
use \Iterator;

interface Message
{

    public function getBody();

    public function getTo();

    public function getFrom();

    public function getSubject();

    public function getParams();

    public function setTo(Iterator $to);

    public function setFrom($from);

    public function setSubject($subject);

    public function setBody(Stream $body);

    public function setParams(array $params);
}
