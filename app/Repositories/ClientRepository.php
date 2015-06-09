<?php

namespace Pushman\Repositories;

use DB;
use Illuminate\Support\Collection;
use Pushman\Ban;
use Pushman\Channel;
use Pushman\Client;
use Pushman\Exceptions\InvalidTokenException;
use Pushman\Exceptions\UserIsBannedException;
use Pushman\Services\DataHandler;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

class ClientRepository
{
    /**
     * @var Collection
     */
    protected static $clients;

    /**
     * Build a basic internal collection to handle our clients.
     */
    public function __construct()
    {
        self::$clients = new Collection();
    }

    /**
     * Handle a connecting client.
     *
     * @param ConnectionInterface $conn
     * @param                     $token
     */
    public function bind(ConnectionInterface $conn, $token)
    {
        try {
            $public_channel = $this->validateToken($token);

            $this->isUserBanned($conn, $public_channel->site->id);

            $this->validateMaxConnections($public_channel, $conn);

            self::$clients->put($conn->resourceId, $conn);

            $userdata = json_encode(DataHandler::getData($conn));

            $client = Client::create([
                'resource_id' => $conn->resourceId,
                'ip'          => $conn->remoteAddress,
                'site_id'     => $public_channel->site->id,
                'userdata'    => $userdata
            ]);
            $client->subscriptions()->attach($public_channel->id, ['event' => 'public']);
            qlog("Client {$conn->resourceId} connected successfully.");
        } catch (InvalidTokenException $ex) {
            qlog("{$conn->resourceId} attmpted to connect with an invalid token.");
            $conn->close();
        } catch (UserIsBannedException $ex) {
            qlog("{$conn->resourceId} attmpted to connect but is banned.");
            $conn->close();
        }
    }

    /**
     * Handle a disconnecting client.
     *
     * @param ConnectionInterface $conn
     */
    public function unbind(ConnectionInterface $conn)
    {
        $resourceId = $conn->resourceId;

        qlog("{$resourceId} disconnected.");
        self::$clients->forget($resourceId);

        $client = Client::where('resource_id', $resourceId)->first();
        if ($client) {
            $id = $client->id;
            DB::table('clients')->where('id', $id)->delete();
            DB::table('channel_client')->where('client_id', $id)->delete();
        }
    }

    /**
     * Validate a clients token.
     *
     * @param $token
     * @return mixed
     * @throws InvalidTokenException
     */
    private function validateToken($token)
    {
        $channel = Channel::where('public', $token)->first();

        if (is_null($channel)) {
            throw new InvalidTokenException('Invalid public token.');
        }

        return $channel;
    }

    /**
     * Handle a client subscribing to an event.
     *
     * @param ConnectionInterface $conn
     * @param Topic               $topic
     */
    public function subscribe(ConnectionInterface $conn, Topic $topic)
    {
        $resource_id = $conn->resourceId;

        $client = Client::where('resource_id', $resource_id)->first();
        if (!$client) {
            qlog("Unverified client attempted to start listening to {$topic}.");
            $conn->close();
        }

        list($original, $channel, $key, $event) = $this->seperatePrivateChannel($topic);
        list($channel, $site) = $this->validatePrivateToken($conn, $channel, $key);

        if (!is_null($channel)) {
            if (!$client->isSubscribed($channel, $event)) {
                $client->subscriptions()->attach($channel->id, ['event' => $event]);
            }
            qlog("{$resource_id} tuned into {$channel->name} on {$site->name}, listening to {$event}.");
        }
    }

    /**
     * Seperate a private channel from its token and format.
     *
     * @param $topic
     * @return array
     */
    private function seperatePrivateChannel($topic)
    {
        $array = [];
        preg_match("/(\S+)\((\S{20})\)\|(\S+)/i", $topic, $array);

        return $array;
    }

    /**
     * Validate a channels token if needed.
     *
     * @param ConnectionInterface $conn
     * @param                     $channel
     * @param                     $key
     * @return array|bool
     */
    private function validatePrivateToken(ConnectionInterface $conn, $channel, $key)
    {
        $resource_id = $conn->resourceId;

        $client = Client::where('resource_id', $resource_id)->first();
        if (!$client) {
            qlog("Unverified client attempted to start listening to {$channel}.");
            $conn->close();

            return false;
        }

        $site = $client->site;

        $channel = Channel::where('name', $channel)->where('site_id', $site->id)->where('public', $key)->first();

        if (!$channel) {
            qlog("Verified client attempted to start listening to {$channel} with bad key.");
            $conn->close();

            return false;
        }

        return [$channel, $site];
    }

    /**
     * Validate the maximum amount of connections to a channel.
     *
     * @param Channel             $channel
     * @param ConnectionInterface $conn
     * @return bool
     */
    private function validateMaxConnections(Channel $channel, ConnectionInterface $conn)
    {
        if ($channel->max_connections == 0) {
            return true;
        }

        $current_active = $channel->current_users();
        if ($current_active >= $channel->max_connections) {
            qlog("Hit the maximum amount of active connections on {$channel->name} for {$channel->site->name}.");
            $conn->close();
        }

        return false;
    }

    /**
     * Handle a user unsubscribing from an event without necessarily disconnecting.
     *
     * @param ConnectionInterface $conn
     * @param                     $topic
     * @return string
     */
    public function unsubscribe(ConnectionInterface $conn, $topic)
    {
        $resource_id = $conn->resourceId;

        $client = Client::where('resource_id', $resource_id)->first();
        if (!$client) {
            qlog("Unverified client attempted to unsubscribe from {$topic}.");
            $conn->close();
        }

        list($original, $channel, $key, $event) = $this->seperatePrivateChannel($topic);
        list($channel, $site) = $this->validatePrivateToken($conn, $channel, $key);

        if ($channel) {
            $client->unsubscribe($channel, $event);
            qlog("{$resource_id} tuned out of {$channel->name} on {$site->name} for {$event}.");

            return $channel->name . '(' . $channel->public . ')|' . $event;
        }
    }

    /**
     * Force disconnect a client by their resource ID.
     *
     * @param $resourceID
     */
    public function forceDisconnect($resourceID)
    {
        $connection = self::$clients[$resourceID];
        $connection->close();
    }

    /**
     * Checking to see if an IP address is banned.
     *
     * @param $conn
     * @param $site_id
     * @throws UserIsBannedException
     */
    private function isUserBanned($conn, $site_id)
    {
        $banned = Ban::where('ip', $conn->remoteAddress)->where('active', 'yes')->where('site_id', $site_id)->first();
        if ($banned) {
            throw new UserIsBannedException('This IP address has been banned for ' . $banned->duration . ' days.');
        }
    }
}
