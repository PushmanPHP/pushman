<?php namespace Pushman\Services;

use Ratchet\ConnectionInterface;

class TokenHandler {

    public static function getToken(ConnectionInterface $conn)
    {
        $url = $conn->wrappedConn->WebSocket->request->getUrl();
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