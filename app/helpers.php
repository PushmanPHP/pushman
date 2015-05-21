<?php

use Pushman\InternalLog;

function qlog($log, $shouldLog = true)
{
    if ($shouldLog) {
        echo("{$log}\n");
        InternalLog::create(['log' => $log]);
    }
}

function user()
{
    return app('auth')->user();
}