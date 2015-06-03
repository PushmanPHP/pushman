<?php namespace Pushman;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model {

    protected $fillable = ['ip', 'duration', 'site_id'];

    public static function ban(Site $site, Client $client)
    {
        self::create([
            'ip'       => $client->ip,
            'site_id'  => $site->id,
            'duration' => '90',
            'active'   => 'yes'
        ]);
    }
}
