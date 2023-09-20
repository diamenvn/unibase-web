<?php

namespace App\Services;

use GuzzleHttp\Client;


class TelegramService
{
    private static $_url = 'https://api.telegram.org/bot';
    private static $_token = '1836288295:AAGy3SzOzbjCm1Fhse9yeuitJ2sidkdcRc8';
    private static $_chat_id = '-590441266';

    public function __construct()
    {
    }

    public static function sendMessage($text)
    {
        $uri = self::$_url . self::$_token . '/sendMessage?parse_mode=html';
        $params = [
            'chat_id' => self::$_chat_id,
            'text' => $text,
        ];
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client([
            'timeout' => 10
        ]);
        $response = $client->request("POST", $uri, $option);
        return json_decode($response->getBody(), true);
    }
}
