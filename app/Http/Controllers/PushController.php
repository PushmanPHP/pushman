<?php namespace Pushman\Http\Controllers;

use Pushman\Http\Requests;
use Pushman\Http\Requests\RunSiteTestRequest;

class PushController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function runTest(RunSiteTestRequest $request)
    {

    }
}
