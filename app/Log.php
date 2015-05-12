<?php namespace Pushman;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

    protected $fillable = ['site_id', 'event_name', 'payload'];

    public function site()
    {
        return $this->belongsTo('Pushman\Site');
    }
}
