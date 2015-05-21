<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Pushman\Http\Requests;
use Pushman\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DashboardController extends Controller {

    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
        $this->middleware('auth');
    }

    /**
     * Present the dashboard
     */
    public function dashboard()
    {
        return view('dashboard');
    }

}
