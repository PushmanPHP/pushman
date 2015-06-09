<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\FlashNotifier;
use Pushman\Http\Requests;
use Pushman\User;

class UsersController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $guard;
    /**
     * @var \Laracasts\Flash\FlashNotifier
     */
    private $flash;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $guard
     * @param \Laracasts\Flash\FlashNotifier   $flash
     */
    public function __construct(Guard $guard, FlashNotifier $flash)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->guard = $guard;
        $this->flash = $flash;
    }

    /**
     * Show a list of users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    /**
     * Show a single user
     *
     * @param \Pushman\User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Promote a user from Waiting to Active.
     *
     * @param \Pushman\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Ban a user.
     *
     * @param \Pushman\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
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
