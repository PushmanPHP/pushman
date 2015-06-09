<?php namespace Pushman\Http\Middleware;

use Closure;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (user()) {
            \App::setLocale(user()->locale);
        }

        return $next($request);
    }
}
