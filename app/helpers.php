<?php

use Pushman\InternalLog;

/**
 * Logs something from Pushman.
 * Outputs to the console, and inserts row into the DB.
 *
 * @param      $log
 * @param bool $shouldLog
 */
function qlog($log, $shouldLog = true)
{
    if ($shouldLog) {
        echo("{$log}\n");
        InternalLog::create(['log' => $log]);
    }
}

/**
 * Gets the currently logged in user.
 *
 * @return mixed
 */
function user()
{
    return app('auth')->user();
}

/**
 * Detects if a string is JSON.
 *
 * @param $string
 * @return bool
 */
function isJson($string)
{
    json_decode($string);

    return (json_last_error() == JSON_ERROR_NONE);
}
