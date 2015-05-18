<?php namespace Pushman;

use DB;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {

    protected $fillable = ['resource_id', 'ip', 'site_id', 'channel_id'];

    public function subscriptions()
    {
        return $this->belongsToMany('Pushman\Channel');
    }

    public function site()
    {
        return $this->belongsTo('Pushman\Site');
    }

    public function isSubscribed(Channel $channel, $event)
    {
        $subCount = $this->subscriptions()
            ->where('channel_id', $channel->id)
            ->where('event', $event)
            ->count();

        return $subCount >= 1;
    }

    public function unsubscribe(Channel $channel, $event)
    {
        DB::table('channel_client')
            ->where('client_id', $this->id)
            ->where('channel_id', $channel->id)
            ->where('event', $event)
            ->delete();
    }
}
