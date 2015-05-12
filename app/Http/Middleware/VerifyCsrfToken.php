<?php namespace Pushman\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->getMethod() === 'POST') {
            $url = $request->url();
            if (str_contains($url, '/api/v')) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }
}
