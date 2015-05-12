<?php namespace Pushman\Http\Controllers;

class WelcomeController extends Controller {

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('welcome');
    }
}
