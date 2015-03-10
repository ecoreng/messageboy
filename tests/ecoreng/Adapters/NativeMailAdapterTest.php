<?php

namespace MessageBoy\Adapters;

function mail($to, $subject, $msg, $headers)
{
    return $to . ':' . $subject . ':' . $msg;
}

class NativeMailAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $ad;

    public function setUp()
    {
        $this->ad = new \MessageBoy\Adapters\NativeMailAdapter;
        $this->ms = new \MessageBoy\SimpleMessage;
    }

    public function testHandle()
    {
        $this->ms->setBodyString('foo');
        $this->ms->setSubject('bar');
        $this->ms->setFrom('moo');
        $this->ms->setToArray(['test@example.com']);
        $status = $this->ad->handle($this->ms);

        $this->assertEquals(true, $status !== null && $status !== false);
        $this->assertEquals('test@example.com:bar:foo', $status);
    }
}
