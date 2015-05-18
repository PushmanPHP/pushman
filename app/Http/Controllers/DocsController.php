<?php namespace Pushman\Http\Controllers;

use Pushman\Http\Requests;

class DocsController extends Controller {

    public function index()
    {
        $className = 'nav-home';
        return view('docs.index', compact('className'));
    }
}
