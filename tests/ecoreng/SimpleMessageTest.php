<?php

namespace ecoreng\Test;

class SimpleMessageTest extends \PHPUnit_Framework_TestCase
{
    protected $ms;

    public function setUp()
    {
        $this->ms = new \ecoreng\MessageBoy\Concrete\SimpleMessage;
    }

    public function testBodyString()
    {
        $this->ms->setBodyString('foo');
        $body = $this->ms->getBody();
        $this->assertInstanceOf('\ecoreng\MessageBoy\Concrete\Stream', $body);
        $this->assertEquals('foo', (string) $body);
    }

    public function testToArray()
    {
        $this->ms->setToArray(['foo' => 'bar']);

        $to = $this->ms->getTo();
        $this->assertInstanceOf('\ArrayIterator', $to);
        $this->assertEquals(1, count($to));
        $this->assertEquals('bar', $to['foo']);
    }
}
