<?php namespace Pushman\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'Pushman\Http\Middleware\VerifyCsrfToken',
        'Pushman\Http\Middleware\AllowCrossDomainRequest',
        'Pushman\Http\Middleware\SetLocale'
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => 'Pushman\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'      => 'Pushman\Http\Middleware\RedirectIfAuthenticated',
        'admin'      => 'Pushman\Http\Middleware\Admin',
        'ownership'  => 'Pushman\Http\Middleware\MustOwnResource',
    ];

}
