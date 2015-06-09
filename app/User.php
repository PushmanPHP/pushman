<?php namespace Pushman;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Returns the user owned sites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sites()
    {
        return $this->hasMany('Pushman\Site');
    }

    /**
     * Is this user allowed to login?
     *
     * @return bool
     */
    public function allowedToLogin()
    {
        $allowedStates = ['admin', 'active'];

        if (in_array($this->status, $allowedStates)) {
            return true;
        }

        return false;
    }

    /**
     * Are they an admin?
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->status === 'admin';
    }

    /**
     * Do they own a specific resource? (Site, Channel, Log, etc);
     *
     * @return bool
     */
    public function owns($type = null, $id = null)
    {
        dd($type, $id);

        return false;
    }
}
