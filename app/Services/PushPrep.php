<?php namespace Pushman\Services;

use Illuminate\Http\Request;
use Pushman\Site;

class PushPrep {

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getHost()
    {
        return rtrim($this->request->server('HTTP_HOST'), '/');
    }

    public function getPort()
    {
        return env('PUSHMAN_PORT', 8080);
    }

    public function usesInternal(Site $site)
    {
        if ( !is_null($site->getInternal())) {
            return true;
        }

        return false;
    }

    public function internalToken(Site $site)
    {
        $channel = $site->getInternal();

        return $channel->public;
    }
}