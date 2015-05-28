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
        $allowedToBypass = ['POST', 'DELETE'];

        if (in_array($request->getMethod(), $allowedToBypass)) {
            $url = $request->url();

            if (preg_match('/\/sites\/\d+\/channels\/\d+\/max/', $url) === 1) {
                return $next($request);
            }

            if (str_contains($url, '/api/')) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }
}
