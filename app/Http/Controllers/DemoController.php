<?php namespace Pushman\Http\Controllers;

use Pushman\Http\Requests;
use Pushman\Site;

class DemoController extends Controller {

    public function index()
    {
        $site = Site::where('name', 'demo')
            ->where('url', 'http://pushman.dfl.mn')
            ->first();
        $className = 'nav-home';

        return view('demo.index', compact('className', 'site'));
    }
}
