<?php

namespace MessageBoy\Adapters;

function file_put_contents($file, $data, $flags)
{
    return $file . ':' . str_replace(PHP_EOL, '', $data) . ':' . $flags;
}

class FileAdapterTest extends \PHPUnit_Framework_TestCase
{

    protected $ad;
    protected $ms;
    protected $add;

    public function setUp()
    {
        $this->add = new \MessageBoy\Adapters\FileAdapter('test', '.tst', true, \FILE_APPEND);
        $this->ad = new \MessageBoy\Adapters\FileAdapter;
        $this->ms = new \MessageBoy\SimpleMessage;
    }

    public function testHandleDefault()
    {
        $this->ms->setBodyString('foo');
        $this->ms->setSubject('bar');
        $this->ms->setFrom('moo');
        $this->ms->setToArray(['test@example.com']);
        $status = $this->ad->handle($this->ms);

        $this->assertEquals(true, is_array($status));
        $pattern = '#' . '\\' . DIRECTORY_SEPARATOR . '[0-9a-zA-Z]{10}.txt\:[0-9]{4}\-[0-9]{2}\-[0-9]{2}T[0-9]{2}'
                . '\:[0-9]{2}\:[0-9]{2}\-[0-9]{2}\:[0-9]{2} \- bar \: moo \-\> test@example\.com \: foo\:0#';
        foreach ($status as $s) {
            $this->assertEquals(1, preg_match($pattern, $s));
        }
    }

    public function testHandleNotDefault()
    {
        $this->ms->setBodyString('foo');
        $this->ms->setSubject('bar');
        $this->ms->setFrom('moo');
        $this->ms->setToArray(['test@example.com']);
        $status = $this->add->handle($this->ms);

        $this->assertEquals(true, is_array($status));
        $pattern = '#test' . '\\' . DIRECTORY_SEPARATOR . 'test\-at\-example\.com\.tst\:[0-9]{4}\-[0-9]{2}\-'
                . '[0-9]{2}T[0-9]{2}\:[0-9]{2}\:[0-9]{2}\-[0-9]{2}\:[0-9]{2} \- bar \: moo \: foo\:8#';
        foreach ($status as $s) {
            $this->assertEquals(1, preg_match($pattern, $s));
        }
    }
}
