<?php

get('/', 'WelcomeController@index');

get('home', 'SitesController@index');
get('sites', 'SitesController@index');

get('settings', 'AuthController@getSettings');

get('sites/{sites}/delete', 'SitesController@destroy');
get('sites/{sites}/regen', 'SitesController@regenTokens');
resource('sites', 'SitesController');

Route::group(['prefix' => 'users'], function () {
    get('/', 'UsersController@index');
    get('{user}', 'UsersController@show');
    get('{user}/promote', 'UsersController@promote');
    get('{user}/ban', 'UsersController@ban');
});

get('log/{log}', 'LogController@show');

Route::group(['prefix' => 'auth'], function () {
    get('login', 'AuthController@getLogin');
    post('login', 'AuthController@postLogin');

    get('register', 'AuthController@getRegister');
    post('register', 'AuthController@postRegister');

    get('logout', 'AuthController@getLogout');
});

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'v0'], function () {
        Route::post('push', 'v0\EventController@push');
    });
});

Route::group(['prefix' => 'docs'], function () {
    get('/', 'DocsController@index');
});
