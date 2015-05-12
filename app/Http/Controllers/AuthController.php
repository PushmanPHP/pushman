<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\FlashNotifier;
use Pushman\Http\Requests;
use Pushman\Http\Requests\CreateNewUserRequest;
use Pushman\Http\Requests\LoginRequest;
use Pushman\User;

class AuthController extends Controller {

    protected $guard;
    protected $flash;

    public function __construct(Guard $guard, FlashNotifier $flash)
    {
        $this->middleware('guest', ['except' => ['getLogout', 'getSettings']]);

        $this->guard = $guard;
        $this->flash = $flash;
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::whereUsername($credentials['username'])->first();
        if ( !$user OR !$user->allowedToLogin()) {
            $this->flash->error('This account is not active.');

            return redirect()->back();
        }

        if ($this->guard->attempt($credentials, $request->has('remember'))) {
            $this->flash->success('Logged in!');

            return redirect()->intended('/home');
        }

        $this->flash->error('Unable to load user details.');

        return redirect('/auth/login')
            ->withInput($request->only('email', 'remember'));
    }

    public function getLogout(Guard $guard)
    {
        $guard->logout();

        return redirect('/');
    }

    public function postRegister(CreateNewUserRequest $request)
    {
        $override = $request->override;

        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ( !empty($override)) {
            if ($override === env('APP_KEY')) {
                $user->status = 'admin';
                $user->save();
            }
        }

        $this->flash->info('Your account has been created. You need to wait for a web master to activate it before logging in.');

        return redirect('/');
    }

    public function getSettings()
    {
        return view('settings.index');
    }
}
