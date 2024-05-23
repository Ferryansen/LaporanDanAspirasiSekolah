<?php

namespace App\Services;

use GuzzleHttp\Client;

class SmsService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('INFOBIP_API_KEY'); 
        $this->baseUrl = 'https://w1wgg1.api.infobip.com'; 
    }

    public function sendSms($to, $message)
    {
        $response = $this->client->post("{$this->baseUrl}/sms/2/text/advanced", [
            'headers' => [
                'Authorization' => 'App ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'messages' => [
                    [
                        'from' => 'SkolahKita',
                        'destinations' => [
                            [
                                'to' => $to
                            ]
                        ],
                        'text' => $message
                    ]
                ]
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}