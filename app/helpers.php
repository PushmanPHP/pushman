<?php

use Carbon\Carbon;
use Pushman\IntLog;
use Pushman\Services\EventHandler;

function qlog($log, $shouldLog = true)
{
    if ($shouldLog) {
        echo("{$log}\n");
        IntLog::create(['log' => $log]);

        $canLog = env('PUSHMAN_LOG', 'no');

        if ($canLog === 'yes') {
            $private = env('PUSHMAN_PRIVATE');
            $timestamp = Carbon::now()->format("Y-m-d H:i:s");
            $jsonLog = ['log' => $log, 'timestamp' => $timestamp];
            (new EventHandler())->handle($private, 'internal', json_encode($jsonLog), false);
        }
    }
}