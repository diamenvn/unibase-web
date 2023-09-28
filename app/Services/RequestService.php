<?php

namespace App\Services;

use GuzzleHttp\Client;


class RequestService
{
    public function __construct()
    {
    }

    public static function send($uri, $params = array(), $method = 'POST')
    {
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client([
            'timeout' => 10
        ]);
        $response = $client->request($method, $uri, $option);
        return json_decode($response->getBody(), true);
    }
}
