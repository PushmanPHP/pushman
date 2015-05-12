<?php namespace Pushman\Http\Controllers;

use Pushman\Http\Requests;

class DocsController extends Controller {

    public function index()
    {
        return view('docs.index');
    }
}
