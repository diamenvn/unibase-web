<?php

namespace App\Services;

use Exception;

class ApiService
{

    public function __construct()
    {
        $this->client = $this->init();
    }

    public function get($url, $body = [], $header = [])
    {
        $response = $this->client->get($url, [
            'timeout' => 5, // Response timeout
            'connect_timeout' => 5, // Connection timeout
        ]);
        return $response;
    }

    public function post($url, $body = [], $header = [])
    {
        $response = $this->client->post($url, [
            'json' => $body,
            'timeout' => 5, // Response timeout
            'connect_timeout' => 5, // Connection timeout
        ]);
        return $response;
    }

    public function parse($response)
    {
        $result['code'] = $response->getStatusCode();
        $result['response'] = json_decode($response->getBody()->getContents());
        $result['success'] = ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) || $result['response']['succeeded'] = true;
        $result['header'] = $response->getHeaders();
        return $result;
    }

    public function init()
    {
        $client = new \GuzzleHttp\Client();
        return $client;
    }
}


?>
