<?php namespace Pushman\Http\Controllers;

class WelcomeController extends Controller {

    /**
     * Setup middleware
     */
    public function __construct()
    {
        $this->middleware('guest', ['only' => 'index']);
        $this->middleware('auth', ['only' => 'settings']);
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $className = 'nav-home';

        return view('welcome', compact('className'));
    }

    public function about()
    {
        $className = 'nav-home';

        return view('about', compact('className'));
    }

    public function settings()
    {
        return view('settings.index');
    }
}
