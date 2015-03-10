<?php

namespace MessageBoy\Adapters;

use \MessageBoy\Interfaces\MessageInterface;
use \MessageBoy\Interfaces\AdapterInterface;

/**
 * Adapter that sends the messages to a file
 *
 * You can pass a base folder (through the constructor) where all your messages will be stored.
 *
 * Pass the extension of the file if you need something different than 'txt'
 *
 * Pass a boolean to define whether you want to use the destinatary as the file name or dont
 *
 * Pass file_put_contents flags as the last parameter to handle the logs differently (use \FILE_APPEND in conjunction
 * with the $destinataryAsName to append all messages to a same destinatary in one filename)
 *
 * The filename generated (if $destinataryAsParam is false) will be a random 10 character string, if you want to change
 * this behavior, pass a filename as a Message param called 'file.filename'
 *
 */
class FileAdapter implements AdapterInterface
{
    protected $folder;
    protected $fileExtension;
    protected $destinataryAsName;
    protected $flags;

    /**
     * Constructor
     *
     * @param string $folder - Folder where the messages are going to be stored
     * @param string $fileExtension - File extension (prepend the dot ie: '.txt' or '.log')
     * @param bool $destinataryAsName - Use destinatary as filename?
     * @param int $flags - file_put_contents flags to use (ie: \FILE_APPEND)
     */
    public function __construct($folder = '', $fileExtension = null, $destinataryAsName = false, $flags = 0)
    {
        $this->folder = $folder;
        $this->fileExtension = $fileExtension ? $fileExtension : '.txt';
        $this->destinataryAsName = $destinataryAsName;
        $this->flags = $flags;
    }

    public function handle(MessageInterface $message)
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
            $to = '';
            if (!$this->destinataryAsName) {
                $to = ' -> ' . trim(implode(', ', (array) $msg->getTo()), ', ');
            }
            $nfilename = preg_replace(['#@#', '/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'], ['-at-','_', '.', ''], $filename);
            $status[] = file_put_contents(
                $this->folder . DIRECTORY_SEPARATOR . $nfilename . $this->fileExtension,
                date('c') . ' - ' . $msg->getSubject() . ' : ' . $msg->getFrom() .  $to . ' : ' .
                $msg->getBody() . PHP_EOL,
                $this->flags
            );
        }
        return $status;
    }
}
