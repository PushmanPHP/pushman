<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Pushman{
/**
 * Pushman\Ban
 *
 * @property integer $id
 * @property integer $site_id
 * @property string $ip
 * @property integer $duration
 * @property string $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereSiteId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Ban whereUpdatedAt($value)
 */
    class Ban
    {
    }
}

namespace Pushman{
/**
 * Pushman\Channel
 *
 * @property integer $id
 * @property integer $site_id
 * @property string $name
 * @property string $public
 * @property string $refreshes
 * @property integer $max_connections
 * @property integer $active_users
 * @property integer $events_fired
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pushman\Site $site
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pushman\Client[] $subscribers
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereSiteId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel wherePublic($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereRefreshes($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereMaxConnections($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereActiveUsers($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereEventsFired($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Channel whereUpdatedAt($value)
 */
    class Channel
    {
    }
}

namespace Pushman{
/**
 * Pushman\Client
 *
 * @property integer $id
 * @property string $resource_id
 * @property string $ip
 * @property integer $site_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pushman\Channel[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pushman\Channel[] $listensto
 * @property-read \Pushman\Site $site
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Client whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Client whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Client whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Client whereSiteId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Client whereUpdatedAt($value)
 */
    class Client
    {
    }
}

namespace Pushman{
/**
 * Pushman\InternalLog
 *
 * @property integer $id
 * @property string $log
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Pushman\InternalLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\InternalLog whereLog($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\InternalLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\InternalLog whereUpdatedAt($value)
 */
    class InternalLog
    {
    }
}

namespace Pushman{
/**
 * Pushman\Site
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $url
 * @property string $private
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Pushman\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pushman\Channel[] $channels
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site wherePrivate($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereUpdatedAt($value)
 */
    class Site
    {
    }
}

namespace Pushman{
/**
 * Pushman\User
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $locale
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pushman\Site[] $sites
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereLocale($value)
 */
    class User
    {
    }
}
