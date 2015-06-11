<?php

namespace Pushman\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{

    protected $except = [
        'sites/*/channels/*/max',
        'api/*',
        'ban/update'
    ];

}
