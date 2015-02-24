<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

class FileAdapter implements \ecoreng\MessageBoy\Adapter
{
    protected $folder;
    protected $fileExtension;
    protected $destinataryAsName;
    protected $flags;

    public function __construct($folder = '', $fileExtension = null, $destinataryAsName = false, $flags = 0)
    {
        $this->folder = $folder;
        $this->fileExtension = $fileExtension ? $fileExtension : '.txt';
        $this->destinataryAsName = $destinataryAsName;
        $this->flags = $flags;
    }

    public function handle(Message $message)
    {
        $msg = $message;
        $filenames = [];
        if (!$this->destinataryAsName) {
            $filenames[] = substr(str_shuffle(MD5(microtime())), 0, 10);
            if (isset($params['file.filename'])) {
                $filenames = [$params['file.filename']];
            }
        } else {
            $filenames = $msg->getTo();
        }

        $status = [];
        foreach ($filenames as $filename) {
            $filename = preg_replace(['#@#', '/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'], ['-at-','_', '.', ''], $filename);
            $status[] = file_put_contents(
                $this->folder . DIRECTORY_SEPARATOR . $filename . $this->fileExtension,
                date('c') . ' - ' . $msg->getSubject() . ' : ' . $msg->getFrom() . ' : ' . $msg->getBody() . PHP_EOL,
                $this->flags
            );
        }
        return $status;
    }
}
