<?php

namespace Pushman\Services;

use League\Url\Url;
use Ratchet\ConnectionInterface;

class DataHandler
{
    public static function getData(ConnectionInterface $conn)
    {
        $url = $conn->wrappedConn->WebSocket->request->getUrl();
        $url = Url::createFromUrl($url);
        $data = $url->query->toArray();

        return $data;
    }
}
