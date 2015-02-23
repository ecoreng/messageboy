<?php

namespace ecoreng\Test;

class MessageDispatcherTest extends \PHPUnit_Framework_TestCase
{
    protected $md;

    public function setUp()
    {
        $this->md = new \ecoreng\MessageBoy\Concrete\MessageDispatcher;
    }

    public function testAddAdapter()
    {
        $mockAdapter = $this->getMockBuilder('ecoreng\MessageBoy\Adapter')->getMock();
        $this->md->registerAdapter($mockAdapter);
        $this->md->registerAdapter($mockAdapter);
        $this->assertEquals(2, count($this->md->getAdapters()));
        foreach ($this->md->getAdapters() as $adapter) {
            $this->assertSame($mockAdapter, $adapter);
        }
    }

    public function testAddAdapterToType()
    {
        $mockAdapter = $this->getMockBuilder('ecoreng\MessageBoy\Adapter')->getMock();
        $this->md->registerAdapter($mockAdapter, 'test');
        $this->md->registerAdapter($mockAdapter, 'test');
        $this->assertEquals(2, count($this->md->getAdaptersByType('test')));
        foreach ($this->md->getAdaptersByType('test') as $adapter) {
            $this->assertSame($mockAdapter, $adapter);
        }
    }

    public function testAddAdapterToGroup()
    {
        $mockAdapter = $this->getMockBuilder('ecoreng\MessageBoy\Adapter')->getMock();
        $this->md->registerAdapter($mockAdapter, null, 'test');
        $this->md->registerAdapter($mockAdapter, null, 'test');
        $this->assertEquals(2, count($this->md->getAdaptersByGroup('test')));
        foreach ($this->md->getAdaptersByGroup('group') as $adapter) {
            $this->assertSame($mockAdapter, $adapter);
        }
    }

    public function testDispatchMessage()
    {
        $mockAdapterType  = $this->getMockBuilder('ecoreng\MessageBoy\Adapter')
            ->setMockClassName('TypeAdapter')
            ->getMock();
        $mockAdapterGroup = $this->getMockBuilder('ecoreng\MessageBoy\Adapter')
            ->setMockClassName('GroupAdapter')
            ->getMock();

        $mockMessage = $this->getMockBuilder('ecoreng\MessageBoy\Message')->getMock();

        $mockAdapterType
            ->expects($this->exactly(2))
            ->method('handle')
            ->with($this->identicalTo($mockMessage));

        $mockAdapterGroup
            ->expects($this->exactly(2))
            ->method('handle')
            ->with($this->identicalTo($mockMessage));

        $this->md->registerAdapter($mockAdapterGroup, null, 'bar');
        $this->md->registerAdapter($mockAdapterType, 'foo');

        $this->md->dispatch($mockMessage);
        $this->md->dispatch($mockMessage, 'foo');
        $this->md->dispatch($mockMessage, null, 'bar');
    }
}
