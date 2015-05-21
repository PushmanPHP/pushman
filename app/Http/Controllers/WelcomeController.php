<?php namespace Pushman\Http\Controllers;

class WelcomeController extends Controller {

    /**
     * Setup middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        return view('settings.index');
    }
}
