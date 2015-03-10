<?php

namespace ecoreng\Test;

class ClosureAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $ad;

    public function setUp()
    {
        $cls = function ($message) {
            return 'successful';
        };
        $this->ad = new \MessageBoy\Adapters\ClosureAdapter($cls);
        $this->ms = new \MessageBoy\SimpleMessage;
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
