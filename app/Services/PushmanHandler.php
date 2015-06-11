<?php

namespace Pushman\Services;

use DB;
use Pushman\Channel;
use Pushman\Repositories\ClientRepository;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\ServerProtocol as WAMP;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;

class PushmanHandler implements WampServerInterface
{
    /**
     * @var \Pushman\Repositories\ClientRepository
     */
    protected $clients;

    /**
     * Set of topics to handle.
     *
     * @var
     */
    protected $topics = [];

    /**
     * Prepare the database and setup the repositories.
     */
    public function __construct()
    {
        DB::table('clients')->truncate();
        DB::table('channel_client')->truncate();
        $this->clients = new ClientRepository();
    }

    /**
     * Called when a new connection opens.
     *
     * @param \Ratchet\ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $token = TokenHandler::getToken($conn);
        $this->clients->bind($conn, $token);
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     *
     * @param ConnectionInterface $conn The socket/connection that is closing/closed
     *
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->unbind($conn);
        $this->checkTopicRequirements($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method.
     *
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     *
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        qlog("ERROR: {$e->getMessage()}");
        // $trace = $e->getTrace();
        // var_dump($trace[0]);
    }

    /**
     * An RPC call has been received.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param string                       $id     The unique ID of the RPC, required to respond to
     * @param string|Topic                 $topic  The topic to execute the call against
     * @param array                        $params Call parameters received from the client
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        qlog("{$conn->resourceId} has tried to make a call.");
        $conn->callError($id, $topic, 'You are not allowed to make calls');
        $conn->close();
    }

    /**
     * A request to subscribe to a topic has been made.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|Topic                 $topic The topic to subscribe to
     */
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->clients->subscribe($conn, $topic);
        $this->topics[$topic->getId()] = $topic;
    }

    /**
     * A request to unsubscribe from a topic has been made.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|Topic                 $topic The topic to unsubscribe from
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->clients->unsubscribe($conn, $topic);
        $this->checkTopicRequirements($conn);
    }

    /**
     * A client is attempting to publish content to a subscribed connections on a URI.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|Topic                 $topic    The topic the user has attempted to publish to
     * @param string                       $event    Payload of the publish
     * @param array                        $exclude  A list of session IDs the message should be excluded from (blacklist)
     * @param array                        $eligible A list of session Ids the message should be send to (whitelist)
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        qlog("Client {$conn->resourceId} is trying to publish something.");
        $conn->close();
    }

    /**
     * @param $event
     */
    public function handleEvent($event)
    {
        $event = json_decode($event, true);

        if ($this->isInternalEvent($event)) {
            $this->handleInternal($event);

            return;
        }

        $channels = $this->getChannels($event['channels']);
        $pureName = $event['event'];
        $payload = $event['payload'];

        foreach ($channels as $channel) {
            $name = TopicHandler::processEventName($pureName, $channel);

            if (!array_key_exists($name, $this->topics)) {
                qlog("Event {$name} receieved. No one to push to.");

                continue;
            }

            $topic = $this->topics[$name];

            if ($channel->name === 'public') {
                foreach ($topic->getIterator() as $connection) {
                    $connection->send(json_encode([WAMP::MSG_EVENT, $pureName, $payload]));
                }
            } else {
                $topic->broadcast($payload);
            }

            qlog("{$name} event pushed out.");
        }
    }

    private function getChannel($id)
    {
        return Channel::find($id);
    }

    private function getChannels($channels)
    {
        $array = [];
        foreach ($channels as $channel) {
            $array[] = Channel::find($channel);
        }

        return $array;
    }

    private function checkTopicRequirements(ConnectionInterface $conn = null)
    {
        foreach ($this->topics as $name => $topic) {
            if (!is_null($conn)) {
                $topic->remove($conn);
            }
            $subscriber_count = count($topic);
            if ($subscriber_count === 0) {
                unset($this->topics[$name]);
            }
        }
    }

    private function isInternalEvent($event)
    {
        if (starts_with($event['event'], 'pushman_internal_event')) {
            return true;
        }

        return false;
    }

    private function handleInternal($event)
    {
        if ($event['event'] === 'pushman_internal_event_client_force_disconnect') {
            $this->clients->forceDisconnect($event['resource_id']);
            qlog('Forced '.$event['resource_id'].' to disconnect.');
        }

        return true;
    }
}
