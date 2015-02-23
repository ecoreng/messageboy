<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

class HttpPostAdapter implements \ecoreng\MessageBoy\Adapter
{
    protected $mappings = [
        'body' => 'body',
        'subject' => 'subject',
        'to' => 'to',
        'from' => 'from'
    ];
    protected $url;
    protected $userAgent = 'MessageBoy HttpPostAdapter';

    public function __construct($url = null, $mappings = [], $userAgent = null)
    {
        if (count($mappings) > 0) {
            $this->mappings = $mappings;
        }

        if ($userAgent) {
            $this->userAgent = $userAgent;
        }
        $this->url = $url;
    }

    public function handle(Message $message)
    {
        $fields = [];

        $data = [
            'body' => (string) $message->getBody(),
            'subject' => $message->getSubject(),
            'from' => $message->getFrom(),
            'to' => implode(',', (array) $message->getTo()),
        ];

        foreach ($this->mappings as $origin => $destination) {
            $fields[$destination] = $data[$origin];
        }

        $params = $message->getParams();
        if (isset($params['httppost.url'])) {
            $this->url = $params['httppost.url'];
        }

        $additionalOptions = [];
        if (isset($params['httppost.additional-options'])) {
            $additionalOptions = $params['httppost.additional-options'];
        }

        $additionalFields = [];
        if (isset($params['httppost.additional-fields'])) {
            $additionalFields = $params['httppost.additional-fields'];
        }

        $curl = curl_init();

        curl_setopt_array(
            $curl,
                $additionalOptions + [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $this->url,
                    CURLOPT_USERAGENT => $this->userAgent,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $additionalFields + $fields,
                ]
        );

        $resp = curl_exec($curl);

        curl_close($curl);
        return $resp;
    }
}