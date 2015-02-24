<?php

namespace ecoreng\MessageBoy\Concrete\Adapters;

use \ecoreng\MessageBoy\Message;

/**
 * Send the message information as an HTTP POST request using Curl
 * 
 * The URL can either be sent through the constructor of the Message param:
 * httppost.url
 * 
 * Additional Curl Options can be set passing them as an array in the Message 
 * param: httppost.additional-options
 * 
 * Additional POST Fields can be passed as the Message param:
 * httppost.additional-fields
 */
class HttpPostAdapter implements \ecoreng\MessageBoy\Adapter
{

    /**
     * Map of the Message fields to the name of the POST field
     * 
     * @var array
     */
    protected $mappings = [
        'body' => 'body',
        'subject' => 'subject',
        'to' => 'to',
        'from' => 'from'
    ];
    
    /**
     * URL for the POST request
     * 
     * @var string
     */
    protected $url;
    
    /**
     * User-agent to use
     * 
     * @var string
     */
    protected $userAgent = 'MessageBoy HttpPostAdapter';

    /**
     * Constructor
     * 
     * @param string $url - HTTP POST URL
     * @param array $mappings - Map of the message fields to POST fields
     * @param string $userAgent - User-agent to use in Curl
     */
    public function __construct($url = null, array $mappings = [], $userAgent = null)
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
                $curl, $additionalOptions + [
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
