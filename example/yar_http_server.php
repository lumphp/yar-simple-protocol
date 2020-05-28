#!/usr/env/php
<?php
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH.'/vendor/autoload.php';

use Lum\Server\ServerHandler;

$config = include_once(ROOT_PATH.'/example/config/http_server_config.php');
foreach ($config as $host => $options) {
    echo "\n[INFO]\t", $host, "\t", print_r($options, true);
    $handlerName = $options['handler'] ?? null;
    echo "\nINFO:", $handlerName;
    $params = $options['params'] ?? [];
    try {
        $hosts = explode(':', $host);
        array_push($hosts, $params);
        $rpc = new $handlerName(...$hosts);
        if (!$rpc || !$rpc instanceof ServerHandler) {
            die("\nnot found server handler!\n");
        }
        $rpc->run();
    } catch (Exception $e) {

    } catch (Error $err) {
        echo sprintf("\nError:[%s]\t", $handlerName), $err->getMessage(), "\t", $err->getTraceAsString();
    }
}


