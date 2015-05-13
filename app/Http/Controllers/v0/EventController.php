<?php namespace Pushman\Http\Controllers\v0;

use Illuminate\Http\Request;
use Pushman\Http\Controllers\Controller;
use Pushman\Http\Requests;
use Pushman\Services\EventHandler;
use Pushman\Site;

class EventController extends Controller {

    public function push(Request $request)
    {
        $event = (new EventHandler())->handle($request->private, $request->type, $request->payload);

        return response()->json($event);
    }
}
