<?php

namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;

class DashboardController extends Controller
{
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
     * Show the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
