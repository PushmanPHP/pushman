<?php namespace Pushman\Http\Controllers;

use Illuminate\Http\Response;
use Pushman\Channel;
use Pushman\Http\Requests;
use Pushman\Http\Requests\CreateChannelRequest;
use Pushman\Http\Requests\UpdateMaxConnectionsRequest;
use Pushman\Respositories\ChannelRepository;
use Pushman\Site;

class ChannelController extends Controller {

    /**
     * Build up middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ownership', ['only' => 'show', 'index', 'regenerate', 'destroy', 'toggle', 'maxConections']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Site $site)
    {
        return view('channels.index', compact('site'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Site $site)
    {
        ChannelRepository::buildPublic($site);

        return view('channels.create', compact('site'));
    }

    /**
     * Store the resource
     *
     * @param \Pushman\Http\Requests\CreateChannelRequest $request
     * @param \Pushman\Site                               $site
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateChannelRequest $request, Site $site)
    {
        $name = $request->name;
        $refreshes = $request->refreshes;
        $max_connections = $request->max_connections;

        $name = $this->validateName($name, $site);

        if ( !$this->validateMaxConnections($max_connections)) {
            return redirect()->back()->withInput()->withErrors(['max_connections' => 'You cannot have that many max connections']);
        }

        ChannelRepository::build($name, $refreshes, $max_connections, $site);

        flash()->success('Added the Channel.');

        return redirect('/sites/' . $site->id . '/channels');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Site $site, Channel $channel)
    {
        return view('channels.show', compact('site', 'channel'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Site $site, Channel $channel)
    {
        $channel->delete();
        flash()->success('Channel deleted.');

        return redirect('/sites/' . $site->id . '/channels');
    }

    /**
     * Validates the amount of max connections a channel is allowed.
     *
     * @param $max_connections
     * @return bool
     */
    private function validateMaxConnections($max_connections)
    {
        $max_connections = (int)$max_connections;

        if (user()->isAdmin()) {
            return true;
        }

        $defined_max = env('PUSHMAN_MAX', 200);

        if ($max_connections > $defined_max OR $max_connections === 0) {
            return false;
        }

        return true;
    }

    /**
     * Validates the channel name.
     *
     * @param $name
     * @param $site
     * @return bool
     * @throws \Pushman\Exceptions\InvalidChannelException
     */
    private function validateName($name, $site)
    {
        return ChannelRepository::validateName($name, $site);
    }

    /**
     * Regenerates a token for a channel.
     *
     * @param \Pushman\Site    $site
     * @param \Pushman\Channel $channel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerate(Site $site, Channel $channel)
    {
        $channel->generateToken();
        $channel->save();

        flash()->warning('Token Regenerated.');

        return redirect()->back();
    }

    /**
     * Turns off auto refreshing for a site.
     *
     * @param \Pushman\Site    $site
     * @param \Pushman\Channel $channel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Site $site, Channel $channel)
    {
        if ($channel->refreshes === 'yes') {
            $channel->refreshes = 'no';
            flash()->warning('Auto Refresh turned <strong>off</strong>.');
        } else {
            $channel->refreshes = 'yes';
            flash()->success('Auto Refresh turned <strong>on</strong>.');
        }

        $channel->save();

        return redirect()->back();
    }

    /**
     * Update the max connections to a channel.
     *
     * @param \Pushman\Site                                      $site
     * @param \Pushman\Channel                                   $channel
     * @param \Pushman\Http\Requests\UpdateMaxConnectionsRequest $request
     * @return mixed
     */
    public function maxConnections(Site $site, Channel $channel, UpdateMaxConnectionsRequest $request)
    {

        if ($this->validateMaxConnections($request->value)) {
            $channel->max_connections = $request->value;
            $channel->save();

            return response()->json(['type' => 'success']);
        } else {
            return response()->json(['type' => 'error', 'message' => 'Invalid Max Connections value!']);
        }
    }
}
