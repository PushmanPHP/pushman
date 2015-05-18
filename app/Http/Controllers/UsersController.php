<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\FlashNotifier;
use Pushman\Http\Requests;
use Pushman\User;

class UsersController extends Controller {

    protected $guard;
    private $flash;

    public function __construct(Guard $guard, FlashNotifier $flash)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->guard = $guard;
        $this->flash = $flash;
    }

    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function promote(User $user)
    {
        if ($user->isAdmin()) {
            $this->flash->error('Unable to change an admin.');

            return redirect()->back();
        }

        $user->status = 'active';
        $user->save();

        $this->flash->success('User has been activated.');

        return redirect()->back();
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            $this->flash->error('Unable to change an admin.');

            return redirect()->back();
        }

        foreach ($user->sites as $site) {
            foreach ($site->channels as $channel) {
                $channel->generateToken();
                $channel->save();
            }
            $site->generateToken();
            $site->save();
        }

        $user->status = 'banned';
        $user->save();

        $this->flash->info('Users site tokens have been refreshed. User has been banned.');

        return redirect()->back();
    }
}
