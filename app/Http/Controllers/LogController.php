<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Pushman\Http\Requests;
use Pushman\IntLog;
use Pushman\Log;
use Pushman\Site;

class LogController extends Controller {

    private $guard;

    public function __construct(Guard $guard)
    {
        $this->middleware('auth');
        $this->guard = $guard;
    }

    public function show(Log $log)
    {
        $this->checkOwnership($log, $this->guard->user());

        return view('logs.show', compact('log'));
    }

    public function all()
    {
        if ( !$this->guard->user()->isAdmin()) {
            throw new UnauthorizedException('You are not authorized to view this.');
        }

        $canLog = env('PUSHMAN_LOG', 'no');

        if($canLog === 'no') {
            abort(500, 'There is no reason to view this page.');
        }

        $private = env('PUSHMAN_PRIVATE');
        $site = Site::wherePrivate($private)->firstOrFail();


        $port = env('PUSHMAN_PORT', 8080);

        $logs = IntLog::orderBy('id', 'DESC')->limit(20)->get();

        return view('logs.all', compact('site', 'logs', 'port'));
    }

    private function checkOwnership($log, $user)
    {
        if ($log->site->user_id !== $user->id AND !$user->isAdmin()) {
            throw new UnauthorizedException('This site does not belong to you.');
        }

        return true;
    }
}
