<?php namespace Pushman;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

    protected $fillable = ['user_id', 'name', 'url'];
    protected $hidden = ['private'];

    public function logs()
    {
        return $this->hasMany('Pushman\Log');
    }

    public function genTokens()
    {
        $this->public = str_random(20);
        $this->private = str_random(60);
    }

    public function setURL($value)
    {
        $this->attributes['url'] = rtrim($value, '/');
    }

    public static function getTokenFromURL($url)
    {
        $tokenFinder = "?token=";
        $tokenPos = strpos($url, $tokenFinder);
        if ($tokenPos === false) {
            return false;
        }

        $strLength = $tokenPos + strlen($tokenFinder);
        $token = substr($url, $strLength);

        return $token;
    }
}
