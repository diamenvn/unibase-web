<?php

$host = env('ENDPOINT_URI');

return [
    'tiktok-sso-uri' => 'https://services.tiktokshop.com/open/authorize?service_id=' . env('TIKTOK_APP_ID')
];