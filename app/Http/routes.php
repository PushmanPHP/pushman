<?php

get('/', 'WelcomeController@index');

get('dashboard', 'DashboardController@dashboard');

resource('sites', 'SiteController', ['only' => ['create', 'store', 'show', 'destroy']]);
get('sites/{sites}/regenerate', 'SiteController@regenerate');
get('sites/{sites}/delete', 'SiteController@destroy');

resource('sites/{sites}/channels', 'ChannelController');
get('sites/{sites}/channels/{channels}/regenerate', 'ChannelController@regenerate');
get('sites/{sites}/channels/{channels}/delete', 'ChannelController@destroy');
get('sites/{sites}/channels/{channels}/toggle', 'ChannelController@toggle');
post('sites/{sites}/channels/{channels}/max', 'ChannelController@maxConnections');

Route::group(['prefix' => 'users'], function () {
    get('/', 'UsersController@index');
    get('{user}', 'UsersController@show');
    get('{user}/promote', 'UsersController@promote');
    get('{user}/ban', 'UsersController@ban');
});

Route::group(['prefix' => 'auth'], function () {
    get('login', 'AuthController@getLogin');
    post('login', 'AuthController@postLogin');

    get('register', 'AuthController@getRegister');
    post('register', 'AuthController@postRegister');

    get('logout', 'AuthController@getLogout');
});

get('documentation', 'DocsController@index');
get('about', 'WelcomeController@about');
get('settings', 'WelcomeController@settings');

post('api/push', 'APIController@push');
post('api/channel', 'APIController@channel');
post('api/channels', 'APIController@channels');