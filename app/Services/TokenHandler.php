<?php

namespace Pushman\Services;

use Ratchet\ConnectionInterface;

class TokenHandler extends DataHandler
{
    public static function getToken(ConnectionInterface $conn)
    {
        $data = parent::getData($conn);
        $token = $data['token'];

        if (!isset($token) or empty($token)) {
            return false;
        }

        return $token;
    }
}
