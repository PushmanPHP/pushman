<?php namespace Pushman\Http\Controllers;

use Illuminate\Http\Response;
use Pushman\Http\Requests;
use Pushman\Http\Requests\CreateSiteRequest;
use Pushman\Repositories\SiteRepository;
use Pushman\Site;

class SiteController extends Controller
{
    /**
     * Start middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ownership', ['only' => ['show', 'delete', 'regenerate']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * @param \Pushman\Http\Requests\CreateSiteRequest $request
     */
    public function store(CreateSiteRequest $request)
    {
        SiteRepository::buildSite($request->name, $request->url, user()->id);

        flash()->success('Site has been built.');

        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Site $site)
    {
        return view('sites.show', compact('site'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Site $site)
    {
        $site->channels()->delete();
        $site->delete();

        flash()->warning('Site deleted!');

        return redirect('/dashboard');
    }

    /**
     * Regenerate the site token.
     *
     * @param \Pushman\Site $site
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerate(Site $site)
    {
        $site->generateToken();
        $site->save();

        flash()->success('Token regenerated.');

        return redirect()->back();
    }
}
