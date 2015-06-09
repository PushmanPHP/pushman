<?php namespace Pushman\Repositories;

use Pushman\Exceptions\InvalidSiteException;
use Pushman\Site;

class SiteRepository
{
    public static function buildSite($name, $url, $userid)
    {
        $url = rtrim($url, '/');
        $existing = self::siteExists($url);

        if ($existing) {
            throw new InvalidSiteException('This site already exists.');
        }

        $site = new Site();
        $site->fill([
            'name'    => $name,
            'url'     => $url,
            'user_id' => $userid,
        ]);
        $site->generateToken();
        $site->save();

        ChannelRepository::buildPublic($site);

        return $site;
    }

    public static function find($private_key)
    {
        return Site::where('private', $private_key)->first();
    }

    public static function siteExists($url)
    {
        $existing_count = Site::where('url', $url)->count();

        return $existing_count >= 1;
    }

    public static function getInternal()
    {
        return Site::where('name', 'internal')
            ->where('url', 'http://internal')
            ->first();
    }
}
