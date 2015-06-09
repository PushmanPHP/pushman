<?php

namespace Pushman\Http\Controllers;

class WelcomeController extends Controller
{
    /**
     * Setup middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
