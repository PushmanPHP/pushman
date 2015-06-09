<?php

namespace Pushman;

use Illuminate\Database\Eloquent\Model;
use Pushman\Interfaces\Ownable;

class Channel extends Model implements Ownable
{
    /**
     * Protected fields hidden from JSON output.
     *
     * @var array
     */
    protected $hidden = ['site_id', 'updated_at', 'subscribers'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo('Pushman\Site');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers()
    {
        return $this->belongsToMany('Pushman\Client')->withPivot('event');
    }

    /**
     * Generate a public token for the channel.
     *
     * @param null $token
     * @param int  $length
     */
    public function generateToken($token = null, $length = 20)
    {
        if (is_null($token)) {
            $token = str_random($length);
        }

        $this->attributes['public'] = $token;
    }

    /**
     * Calculate the amount of events to have passed down this channel.
     *
     * @return int
     */
    public function events_fired()
    {
        return $this->events_fired;
    }

    /**
     * Number of active users on the channel.
     *
     * @return mixed
     */
    public function current_users()
    {
        $count = 0;
        $counted_users = [];
        foreach ($this->subscribers as $subscriber) {
            if (!in_array($subscriber->id, $counted_users)) {
                $count++;
                $counted_users[] = $subscriber->id;
            }
        }

        return $count;
    }

    /**
     * HTML htmler for refreshes.
     *
     * @return string
     */
    public function refreshes()
    {
        if ($this->refreshes === 'yes') {
            return '<span class="label label-info">Yes</span>';
        }

        return '<span class="label label-warning">No</span>';
    }

    /**
     * Checks to see if this resources is owned by a user.
     *
     * @param \Pushman\User $user
     *
     * @return mixed
     */
    public function ownedBy(User $user)
    {
        return $this->site->ownedBy($user);
    }

    /**
     * Returns the true active_user count.
     *
     * @return mixed
     */
    public function getActiveUsersAttribute()
    {
        return $this->current_users();
    }
}
