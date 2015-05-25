<?php namespace Pushman\Http\Controllers;

use Pushman\Channel;
use Pushman\Client;
use Pushman\Http\Requests;
use Pushman\Site;

class SubscriberController extends Controller {

    public function __construct()
    {
        $this->middleware('ownership');
    }

    public function show(Site $site)
    {
        $subscribers = Client::with('listensto')->where('site_id', $site->id)->get();

        return view('subscribers.show', compact('site', 'subscribers'));
    }

    public function disconnect(Site $site, $resourceID)
    {
        $pushmanPayload = [
            'event'       => 'pushman_internal_event_client_force_disconnect',
            'resource_id' => $resourceID
        ];
        $pushmanPayload = json_encode($pushmanPayload);

        $port = env('PUSHMAN_INTERNAL', 5555);
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pushman');
        $socket->connect("tcp://localhost:" . $port);
        $socket->send($pushmanPayload);

        flash()->warning('Forced client ' . $resourceID . ' to disconnect.');

        return redirect()->back();
    }
}
