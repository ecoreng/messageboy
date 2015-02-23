<?php

namespace ecoreng\Test;

class NativeMailAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $ad;

    public function setUp()
    {
        $cls = function($message) {
            return 'successful';
        };
        $this->ad = new \ecoreng\MessageBoy\Concrete\Adapters\ClosureAdapter($cls);
        $this->ms = new \ecoreng\MessageBoy\Concrete\SimpleMessage;
    }

    public function testHandle()
    {
        $this->ms->setBodyString('foo');
        $this->ms->setSubject('bar');
        $this->ms->setFrom('moo');
        $this->ms->setToArray(['test@example.com']);
        $status = $this->ad->handle($this->ms);

        $this->assertEquals('successful', $status);
    }
}