<?php namespace Pushman\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Laracasts\Flash\FlashNotifier;
use Pushman\Http\Requests;
use Pushman\Http\Requests\NewSiteRequest;
use Pushman\Site;
use Pushman\User;

class SitesController extends Controller {

    protected $guard;
    protected $flash;

    public function __construct(Guard $guard, FlashNotifier $flash)
    {
        $this->middleware('auth');
        $this->guard = $guard;
        $this->flash = $flash;
    }

    public function index()
    {
        $user = $this->guard->user();

        return view('sites.index', compact('user'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(NewSiteRequest $request)
    {
        $user = $this->guard->user();

        $site = new Site();
        $site->fill([
            'user_id' => $user->id,
            'name'    => $request->name,
            'url'     => $request->url
        ]);
        $site->setURL($request->url);
        $site->genTokens();

        $site->save();

        $this->flash->success('Site built!');

        return redirect('/sites');
    }

    public function show(Site $site)
    {
        $this->checkOwnership($site, $this->guard->user());

        return view('sites.show', compact('site'));
    }

    public function destroy(Site $site)
    {
        $user = $this->guard->user();

        $this->checkOwnership($site, $user);

        $site->delete();
        $this->flash->success('Site deleted.');

        return redirect('sites');
    }

    public function regenTokens(Site $site)
    {
        $this->checkOwnership($site, $this->guard->user());

        $site->genTokens();
        $site->save();
        $this->flash->success('Regenerated tokens.');

        return redirect()->back();
    }

    private function checkOwnership(Site $site, User $user)
    {
        if ($site->user_id !== $user->id AND !$user->isAdmin()) {
            throw new UnauthorizedException('This site does not belong to you.');
        }

        return true;
    }
}
