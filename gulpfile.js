var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.styles([
        'vendor/sweet-alert.css',
        'vendor/bootstrap-editable.css',
    	'app.css'
    ], null, 'public/css');

    mix.scripts([
        'vendor/jquery-2.1.4.min.js',
        '../../resources/assets/bootstrap/assets/javascripts/bootstrap.js',
        'vendor/sweet-alert.js',
        'vendor/bootstrap-editable.js',
    	'vendor/autobahn.min.js',
    	'site.js'
    ], null, 'public/js');
});
