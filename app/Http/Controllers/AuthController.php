<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\FlashNotifier;
use Pushman\Http\Requests;
use Pushman\Http\Requests\CreateNewUserRequest;
use Pushman\Http\Requests\LoginRequest;
use Pushman\User;

class AuthController extends Controller {

    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $guard;
    /**
     * @var \Laracasts\Flash\FlashNotifier
     */
    protected $flash;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $guard
     * @param \Laracasts\Flash\FlashNotifier   $flash
     */
    public function __construct(Guard $guard, FlashNotifier $flash)
    {
        $this->middleware('guest', ['except' => ['getLogout', 'getSettings']]);

        $this->guard = $guard;
        $this->flash = $flash;
    }

    /**
     * Show the login page
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        $className = 'nav-home';

        return view('auth.login', compact('className'));
    }

    /**
     * Show the register page
     *
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {
        $className = 'nav-home';

        return view('auth.register', compact('className'));
    }

    /**
     * Process a login request
     *
     * @param \Pushman\Http\Requests\LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::whereUsername($credentials['username'])->first();

        if ( !$user) {
            $this->flash->error('Unable to load user details.');

            return redirect('/auth/login')
                ->withInput($request->only('email', 'remember'));
        }

        if ( !$user->allowedToLogin()) {
            $this->flash->error('This account is not active.');

            return redirect('/auth/login')
                ->withInput($request->only('email', 'remember'));
        }

        if ($this->guard->attempt($credentials, $request->has('remember'))) {
            $this->flash->success('Logged in!');

            return redirect()->intended('/dashboard');
        }
    }

    /**
     * Process a logout
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        $this->guard->logout();

        return redirect('/');
    }

    /**
     * Process a register attempt
     *
     * @param \Pushman\Http\Requests\CreateNewUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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
                $this->guard->login($user);
                $this->flash->success('Logged in!');
            }
        } else {
            $this->flash->info('Your account has been created. You need to wait for a web master to activate it before logging in.');
        }

        return redirect('/');
    }

    /**
     * Get the settings page
     *
     * @return \Illuminate\View\View
     */
    public function getSettings()
    {
        return view('settings.index');
    }
}
