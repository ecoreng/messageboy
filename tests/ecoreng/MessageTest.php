<?php

namespace ecoreng\Test;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    protected $ms;

    public function setUp()
    {
        $this->ms = new \ecoreng\MessageBoy\Concrete\Message;
    }

    public function testBody()
    {
        $mockStream = $this->getMockBuilder('\ecoreng\MessageBoy\Concrete\Stream')
            ->disableOriginalConstructor()
            ->getMock();
        $this->ms->setBody($mockStream);
        $this->assertSame($mockStream, $this->ms->getBody());
    }

    public function testTo()
    {
        $mockIterator = $this->getMockBuilder('\ArrayIterator')
            ->getMock();
        $this->ms->setTo($mockIterator);
        $this->assertSame($mockIterator, $this->ms->getTo());
    }

    public function testFrom()
    {
        $this->ms->setFrom('from');
        $this->assertEquals('from', $this->ms->getFrom());
    }

    public function testSubject()
    {
        $this->ms->setSubject('subject');
        $this->assertEquals('subject', $this->ms->getSubject());
    }

    public function testParams()
    {
        $this->ms->setParams(['foo' => 'bar']);
        $params = $this->ms->getParams();
        $this->assertEquals(true, is_array($params));
        $this->assertEquals(1, count($params));
        $this->assertEquals('bar', $params['foo']);
    }
}
