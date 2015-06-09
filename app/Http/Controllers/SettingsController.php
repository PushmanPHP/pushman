<?php

namespace Pushman\Http\Controllers;

use Pushman\Http\Requests\SettingsRequest;

class SettingsController extends Controller
{
    /**
     * Build middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the settings page.
     *
     * @return Response
     */
    public function index()
    {
        $locales = [
            'en' => 'English',
            'fr' => 'French',
        ];

        return view('settings.index', compact('locales'));
    }

    public function store(SettingsRequest $settings)
    {
        $user = user();
        $locale = $settings->only('locale');
        $user->locale = $locale['locale'];
        $user->save();

        flash()->success('Updated your locale.');

        return redirect()->back();
    }
}
