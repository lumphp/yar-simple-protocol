#!/usr/env/php
<?php
ini_set("yar.timeout", 6000000);
function callback($retval, $callinfo)
{
    if ($callinfo == null) {
        echo "现在, 所有的请求都发出去了, 还没有任何请求返回\n";
    } else {
        echo "这是一个远程调用的返回, 调用的服务名是", $callinfo["method"], ". 调用的sequence是 ", $callinfo["sequence"], "\n";
        var_dump($retval);
    }
}

function error_callback($type, $error, $callinfo)
{
    error_log($error);
}

Yar_Concurrent_Client::call(
    "http://127.0.0.1:9502/test/",
    "query",
    [999],
    "callback",
    null,
    [YAR_OPT_PACKAGER => "msgpack"]
);
Yar_Concurrent_Client::call(
    "http://127.0.0.1:9502/test/",
    "query",
    [888],
    "callback",
    null,
    [YAR_OPT_PACKAGER => "php"]
);   // if the callback is not specificed,
// callback in loop will be used
Yar_Concurrent_Client::call(
    "http://127.0.0.1:9502/test/",
    "query",
    [777],
    "callback",
    null,
    [YAR_OPT_PACKAGER => "json"]
);
//this server accept json packager
Yar_Concurrent_Client::call(
    "http://127.0.0.1:9502/test/",
    "query",
    [666],
    "callback",
    null,
    [YAR_OPT_TIMEOUT => 1, YAR_OPT_PACKAGER => "msgpack"]
);
Yar_Concurrent_Client::loop("callback", "error_callback");