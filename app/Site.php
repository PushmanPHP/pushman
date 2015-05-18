<?php namespace Pushman;

use Illuminate\Database\Eloquent\Model;
use Pushman\Interfaces\Ownable;
use Pushman\Respositories\ChannelRepository;

class Site extends Model implements Ownable {

    /**
     * Hidden fields
     *
     * @var array
     */
    protected $hidden = ['private'];
    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Pushman\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany('Pushman\Channel');
    }

    /**
     * Generate a private token for the site.
     *
     * @param null $token
     * @param int  $length
     */
    public function generateToken($token = null, $length = 60)
    {
        if (is_null($token)) {
            $token = str_random($length);
        }

        $this->attributes['private'] = $token;
    }

    /**
     * UI Helper for creating a Select option list of channels.
     *
     * @return array
     */
    public function getChannelNames()
    {
        $array = [];

        foreach ($this->channels as $channel) {
            $array[$channel->name] = $channel->name;
        }

        return $array;
    }

    /**
     * The amount of max connections for this site.
     *
     * @return int
     */
    public function max_connections()
    {
        $channel = ChannelRepository::getPublic($this);

        return $channel->max_connections;
    }

    /**
     * How many events have been fired for the entire site.
     *
     * @return int
     */
    public function events_fired()
    {
        $events = 0;

        foreach ($this->channels as $channel) {
            $events += $channel->events_fired();
        }

        return $events;
    }

    /**
     * The number of users on the site right now.
     *
     * @return int
     */
    public function current_users()
    {
        $users = 0;

        foreach ($this->channels as $channel) {
            $users += $channel->current_users();
        }

        return $users;
    }

    /**
     * @return mixed
     */
    public function getInternal()
    {
        return $this->channels()->where('name', 'pushmaninternal')->first();
    }

    /**
     * Checks to see if this resources is owned by a user.
     *
     * @param \Pushman\User $user
     * @return mixed
     */
    public function ownedBy(User $user)
    {
        return $this->user_id === $user->id;
    }
}
