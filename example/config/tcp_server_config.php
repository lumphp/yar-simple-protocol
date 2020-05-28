<?php
/**
 * normal yar server config
 */
return [
    '127.0.0.1:9501' => [
        'handler' => '\\Lum\\Swoole\\TcpServerHandler',
        'params' => [
            'workNum' => 1,
            'protocol' => '\\Lum\\Yar\\YarHandler',
            'services' => '\\Lum\\Service\\TestService',
        ],
    ],
];