<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

function curl_init()
{
    return new \ArrayObject;
}

function curl_setopt_array($curl, $options)
{
    $curl->append([
        'transfer' => $options[CURLOPT_RETURNTRANSFER],
        'url' => $options[CURLOPT_URL],
        'agent' => $options[CURLOPT_USERAGENT],
        'post' => $options[CURLOPT_POST],
        'fields' => $options[CURLOPT_POSTFIELDS],
        'all' => $options
    ]);
}

function curl_exec($curl)
{
    return $curl;
}

function curl_close($curl)
{
    unset($curl);
}

class HttpPostAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $ad;

    public function setUp()
    {
        $this->ad = new \ecoreng\MessageBoy\Concrete\Adapters\HttpPostAdapter('http://example.com');
        $this->ms = new \ecoreng\MessageBoy\Concrete\SimpleMessage;
    }

    public function testHandle()
    {
        
        $this->ms->setBodyString('foo');
        $this->ms->setSubject('bar');
        $this->ms->setFrom('moo');
        $this->ms->setToArray(['test@example.com']);
        $status = $this->ad->handle($this->ms);
        $this->assertInstanceOf('\ArrayObject', $status);
        
        $this->assertEquals(1, $status[0]['transfer']);
        $this->assertEquals('http://example.com', $status[0]['url']);
        $this->assertEquals('MessageBoy HttpPostAdapter', $status[0]['agent']);
        $this->assertEquals(1, $status[0]['post']);

        $this->assertEquals(true, is_array($status[0]['fields']));

        $this->assertEquals('foo', $status[0]['fields']['body']);
        $this->assertEquals('bar', $status[0]['fields']['subject']);
        $this->assertEquals('test@example.com', $status[0]['fields']['to']);
        $this->assertEquals('moo', $status[0]['fields']['from']);
    }

    public function testHandleNotDefault()
    {
        $this->ad = new \ecoreng\MessageBoy\Concrete\Adapters\HttpPostAdapter(
            'http://example2.com',
            ['body' => 'mensaje', 'subject' => 'titulo', 'to' => 'para', 'from' => 'de'],
            'superbot'
        );

        $this->ms->setBodyString('foo');
        $this->ms->setSubject('bar');
        $this->ms->setFrom('moo');
        $this->ms->setToArray(['test@example.com']);
        $this->ms->setParams([
            'httppost.url' => 'http://127.0.0.1',
            'httppost.additional-options' => [CURLOPT_FOLLOWLOCATION => true],
            'httppost.additional-fields' => ['moo' => 'car']
            ]);
        $status = $this->ad->handle($this->ms);
        
        $this->assertInstanceOf('\ArrayObject', $status);

        $this->assertEquals(1, $status[0]['transfer']);
        $this->assertEquals('http://127.0.0.1', $status[0]['url']);
        $this->assertEquals('superbot', $status[0]['agent']);
        $this->assertEquals(1, $status[0]['post']);
        $this->assertEquals(true, $status[0]['all'][CURLOPT_FOLLOWLOCATION]);

        $this->assertEquals(true, is_array($status[0]['fields']));

        $this->assertEquals('foo', $status[0]['fields']['mensaje']);
        $this->assertEquals('bar', $status[0]['fields']['titulo']);
        $this->assertEquals('test@example.com', $status[0]['fields']['para']);
        $this->assertEquals('moo', $status[0]['fields']['de']);
        $this->assertEquals('car', $status[0]['fields']['moo']);

    }
}
