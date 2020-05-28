#!/usr/env/php
<?php
require_once __DIR__.'/../vendor/autoload.php';
try {
    $test = new Yar_Client("tcp://127.0.0.1:9501");
    //var_dump($test->query("9999"));
    var_dump($test->query("9999"));
} catch (Yar_Server_Exception $E) {
    echo "\n[SERVER ERROR]\t", $E->getMessage();
} catch (Yar_Client_Exception $e) {
    echo "\n[CLIENT ERROR]\t", $e->getMessage();
}