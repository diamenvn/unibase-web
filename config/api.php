<?php

$host = env('ENDPOINT_URI');

return [
    'store' => [
        'list' => $host . '/api/Store/fetch',
        'create' => $host . '/api/Store',
        'setting' => $host . '/api/Store/setting'
    ],
    'product' => [
        'list' => $host . '/api/Product/fetch'
    ],
    'order' => [
        'list' => $host . '/api/Order/fetch'
    ],
];