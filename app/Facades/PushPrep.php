<?php namespace Pushman\Facades;

use Illuminate\Support\Facades\Facade;

class PushPrep extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'pushprep';
    }
}