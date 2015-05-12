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
        $this->guard = $guard;
        $this->flash = $flash;
    }

    public function index()
    {
        $this->checkForAdmin();

        $users = User::with('sites')->get();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $this->checkForAdmin();

        return view('users.show', compact('user'));
    }

    private function checkForAdmin()
    {
        if ($this->guard->user()->status !== 'admin') {
            abort(403, 'Unauthorized.');
        }
    }

    public function promote(User $user)
    {
        $user->status = 'active';
        $user->save();

        $this->flash->success('User has been activated.');

        return redirect('/users/' . $user->id);
    }

    public function ban(User $user)
    {
        foreach ($user->sites as $site) {
            $site->genTokens();
            $site->save();
        }

        $user->status = 'banned';
        $user->save();

        $this->flash->info('Users site tokens have been refreshed. User has been banned.');

        return redirect()->back();
    }
}
