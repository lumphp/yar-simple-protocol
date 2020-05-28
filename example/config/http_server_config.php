<?php
/**
 * normal yar server config
 */
return [
    '127.0.0.1:9502' => [
        'handler' => '\\Lum\\Swoole\\HttpServerHandler',
        'params' => [
            'protocol' => '\\Lum\\Yar\\YarHandler',
            'services' => [
                'test' => '\\Lum\\Service\\TestService',
            ],
        ],
    ],
];