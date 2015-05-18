<?php namespace Pushman\Services;

use Pushman\Channel;
use Ratchet\ConnectionInterface;

class TopicHandler {

    public static function processEventName($name, Channel $channel)
    {
        if ($channel->name == 'public)') {
            return 'public(' . $channel->public . ')|' . $name;
        }

        return $channel->name . '(' . $channel->public . ')|' . $name;
    }

    public static function getTopicType($topic)
    {
        $matches = preg_match("/\S+\(\S{20}\)\|\S+/i", $topic);

        if ($matches === 0) {
            return 'public';
        }

        return 'private';
    }

    public static function renamePublic($topic, ConnectionInterface $conn)
    {
        $token = TokenHandler::getToken($conn);
        $channel = Channel::where('public', $token)->first();

        return 'public(' . $channel->public . ')|' . $topic;
    }
}