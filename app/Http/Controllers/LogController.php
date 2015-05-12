<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Pushman\Http\Requests;
use Pushman\Log;

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

    private function checkOwnership($log, $user)
    {
        if ($log->site->user_id !== $user->id AND !$user->isAdmin()) {
            throw new UnauthorizedException('This site does not belong to you.');
        }

        return true;
    }
}
