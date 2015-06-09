<?php

namespace Pushman\Http\Controllers;

use Pushman\Ban;
use Pushman\Http\Requests\EditBanRequest;
use Pushman\Site;

class BanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ownership');
    }

    public function index(Site $site)
    {
        $bans = Ban::where('site_id', $site->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('bans.index', compact('site', 'bans'));
    }

    public function unban(Site $site, $ban_id)
    {
        Ban::find($ban_id)->delete();

        flash()->success('Unbanned this IP address.');

        return redirect()->back();
    }

    public function update(EditBanRequest $request)
    {
        $ban = Ban::findOrFail($request->id);
        $ban->duration = $request->duration;
        $ban->ip = $request->ip;
        $ban->active = $request->active;
        $ban->save();
    }
}
