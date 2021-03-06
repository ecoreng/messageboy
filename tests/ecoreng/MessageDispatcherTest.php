<?php

namespace ecoreng\Test;

class MessageDispatcherTest extends \PHPUnit_Framework_TestCase
{
    protected $md;

    public function setUp()
    {
        $this->md = new \MessageBoy\MessageDispatcher;
    }

    public function testAddAdapter()
    {
        $mockAdapter = $this->getMockBuilder('MessageBoy\Interfaces\AdapterInterface')->getMock();
        $this->md->registerAdapter($mockAdapter);
        $this->md->registerAdapter($mockAdapter);
        $this->assertEquals(2, count($this->md->getAdapters()));
        foreach ($this->md->getAdapters() as $adapter) {
            $this->assertSame($mockAdapter, $adapter);
        }
    }

    public function testAddAdapterToType()
    {
        $mockAdapter = $this->getMockBuilder('MessageBoy\Interfaces\AdapterInterface')->getMock();
        $this->md->registerAdapter($mockAdapter, 'test');
        $this->md->registerAdapter($mockAdapter, 'test');
        $this->assertEquals(2, count($this->md->getAdaptersByType('test')));
        foreach ($this->md->getAdaptersByType('test') as $adapter) {
            $this->assertSame($mockAdapter, $adapter);
        }
    }

    public function testAddAdapterToGroup()
    {
        $mockAdapter = $this->getMockBuilder('MessageBoy\Interfaces\AdapterInterface')->getMock();
        $this->md->registerAdapter($mockAdapter, null, 'test');
        $this->md->registerAdapter($mockAdapter, null, 'test');
        $this->assertEquals(2, count($this->md->getAdaptersByGroup('test')));
        foreach ($this->md->getAdaptersByGroup('group') as $adapter) {
            $this->assertSame($mockAdapter, $adapter);
        }
    }

    public function testAddAdapterToBoth()
    {
        $mockAdapter = $this->getMockBuilder('MessageBoy\Interfaces\AdapterInterface')->getMock();
        $this->md->registerAdapter($mockAdapter, 'foo', 'moo');
        $this->md->registerAdapter($mockAdapter, 'foo', 'bar');
        $this->assertEquals(2, count($this->md->getAdaptersByType('foo')));

        $this->assertEquals(1, count($this->md->getAdaptersByGroup('moo')));
        $this->assertEquals(1, count($this->md->getAdaptersByGroup('bar')));

    }

    public function testDispatchMessage()
    {
        $mockAdapterType  = $this->getMockBuilder('MessageBoy\Interfaces\AdapterInterface')
            ->setMockClassName('TypeAdapter')
            ->getMock();
        $mockAdapterGroup = $this->getMockBuilder('MessageBoy\Interfaces\AdapterInterface')
            ->setMockClassName('GroupAdapter')
            ->getMock();

        $mockMessage = $this->getMockBuilder('MessageBoy\Interfaces\MessageInterface')->getMock();

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

        $dis = [];
        $disEq =[2, 1, 1];
        $dis[] = $this->md->dispatch($mockMessage);
        $dis[] = $this->md->dispatch($mockMessage, 'foo');
        $dis[] = $this->md->dispatch($mockMessage, null, 'bar');
        foreach ($dis as $key => $result) {
            $this->assertEquals($disEq[$key], $result);
        }
    }
}
