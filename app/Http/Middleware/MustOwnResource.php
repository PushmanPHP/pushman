<?php namespace Pushman\Http\Middleware;

use Closure;
use Pushman\Exceptions\InvalidRequestException;
use Pushman\Interfaces\Ownable;

class MustOwnResource
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
        $allowed = true;
        $user = $request->user();

        if ($user->isAdmin()) {
            return $next($request);
        }

        $resources = [];

        $site = $request->route()->getParameter('sites');
        if (!is_null($site)) {
            $resources[] = $site;
        }

        $channel = $request->route()->getParameter('channels');
        if (!is_null($channel)) {
            $resources[] = $channel;
        }

        foreach ($resources as $resource) {
            if (!$resource instanceof Ownable) {
                throw new InvalidRequestException('Cannot check ownership on this object.');
            }
            if (!$resource->ownedBy($user)) {
                $allowed = false;
            }
        }

        if ($allowed) {
            return $next($request);
        }

        flash()->warning('You do not own that resource.');

        return redirect('/dashboard');
    }
}
