<?php namespace Pushman\Services;

use League\Url\Url;
use Ratchet\ConnectionInterface;

class TokenHandler
{
    public static function getToken(ConnectionInterface $conn)
    {
        $url = $conn->wrappedConn->WebSocket->request->getUrl();
        $url = Url::createFromUrl($url);
        $token = $url->query->getValue('token');

        if (!isset($token) or empty($token)) {
            return false;
        }

        return $token;
    }
}
