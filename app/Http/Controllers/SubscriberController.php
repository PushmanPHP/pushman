<?php

namespace Pushman\Http\Controllers;

use Pushman\Ban;
use Pushman\Client;
use Pushman\Site;

class SubscriberController extends Controller
{
    /**
     * Build middleware.
     */
    public function __construct()
    {
        $this->middleware('ownership');
    }

    /**
     * Shows a sites subscribers.
     *
     * @param \Pushman\Site $site
     *
     * @return \Illuminate\View\View
     */
    public function show(Site $site)
    {
        $subscribers = Client::with('listensto')->where('site_id', $site->id)->get();

        return view('subscribers.show', compact('site', 'subscribers'));
    }

    /**
     * Force disconnects someone.
     *
     * @param \Pushman\Site $site
     * @param               $resourceID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnect(Site $site, $resourceID)
    {
        $pushmanPayload = [
            'event'       => 'pushman_internal_event_client_force_disconnect',
            'resource_id' => $resourceID,
        ];
        $pushmanPayload = json_encode($pushmanPayload);

        $port = env('PUSHMAN_INTERNAL', 5555);
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pushman');
        $socket->connect('tcp://localhost:'.$port);
        $socket->send($pushmanPayload);

        flash()->warning('Forced client '.$resourceID.' to disconnect.');

        return redirect()->back();
    }

    public function ban(Site $site, $resourceID)
    {
        $client = Client::where('resource_id', $resourceID)->first();

        Ban::ban($site, $client);

        $this->disconnect($site, $resourceID);

        flash()->error('Forced client '.$resourceID.' to disconnect and he was banned.');

        return redirect()->back();
    }
}
