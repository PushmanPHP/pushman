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
 * Pushman\Site
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property string $name 
 * @property string $url 
 * @property string $public 
 * @property string $private 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site wherePublic($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site wherePrivate($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\Site whereUpdatedAt($value)
 */
	class Site {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Pushman\Site[] $sites 
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Pushman\User whereUpdatedAt($value)
 */
	class User {}
}

