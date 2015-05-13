<?php namespace Pushman\Services;

use Pushman\Site;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;

class PushmanHandler implements WampServerInterface {

    /**
     * Holds the clients.
     * @var
     */
    protected $clients;
    /**
     * Holds the subscribed topics.
     * @var array
     */
    protected $subscribedTopics = [];

    /**
     * Constructs the client library.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $incoming_url = $conn->wrappedConn->WebSocket->request->getUrl();

        $token = Site::getTokenFromURL($incoming_url);
        $site = Site::where('public', $token)->first();

        if ($token === false OR is_null($site)) {
            qlog("{$conn->resourceId} tried to connect on token {$token} but failed.");
            $conn->close();
        } else {
            $this->clients->attach($conn);
            qlog("New connection! ({$conn->resourceId}) with token ID: {$token}, site: {$site->url}");
        }
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        qlog("Connection {$conn->resourceId} has disconnected.");
        foreach ($this->subscribedTopics as $type => $topic) {
            $topic->remove($conn);
            qlog("Checking {$topic} for broadcast requirement.");
            $subCount = count($topic);
            qlog("{$topic} still has {$subCount} subscribers.");
            if (count($topic) == 0) {
                qlog("{$topic} topic doesn't have any more subs. Removing it.");
                unset($this->subscribedTopics[$type]);
            }
        }
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception          $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        qlog("ERROR: " . $e->getMessage());
    }

    /**
     * An RPC call has been received
     * @param ConnectionInterface $conn
     * @param string              $id     The unique ID of the RPC, required to respond to
     * @param string|Topic        $topic  The topic to execute the call against
     * @param array               $params Call parameters received from the client
     */
    function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        qlog("{$conn->resourceId} has tried to call topic {$topic->getId()}");
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    /**
     * A request to subscribe to a topic has been made
     * @param ConnectionInterface $conn
     * @param string|Topic        $topic The topic to subscribe to
     */
    function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $incoming_url = $conn->wrappedConn->WebSocket->request->getUrl();

        $token_string = Site::getTokenFromURL($incoming_url);
        $site = Site::where('public', $token_string)->first();

        if (is_null($site)) {
            $this->onClose($conn);
        }

        $id = $topic->getId();
        $topic_id = $id . '.' . $token_string;

        $this->subscribedTopics[$topic_id] = $topic;
        qlog("{$conn->resourceId} has subscribed to topic {$topic_id}");
    }

    /**
     * A request to unsubscribe from a topic has been made
     * @param ConnectionInterface $conn
     * @param string|Topic        $topic The topic to unsubscribe from
     */
    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        qlog("{$conn->resourceId} has unsubscribed from topic {$topic->getId()}");
    }

    /**
     * A client is attempting to publish content to a subscribed connections on a URI
     * @param ConnectionInterface $conn
     * @param string|Topic        $topic    The topic the user has attempted to publish to
     * @param string              $event    Payload of the publish
     * @param array               $exclude  A list of session IDs the message should be excluded from (blacklist)
     * @param array               $eligible A list of session Ids the message should be send to (whitelist)
     */
    function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        qlog("Client {$conn->resourceId} is trying to publish something?");
        $conn->close();
    }

    /**
     * Handles the event to push onto the client.
     * @param $event
     */
    public function handleEvent($event)
    {
        $eventData = json_decode($event, true);

        $site = Site::where('private', $eventData['private'])->first();
        if (is_null($site)) {
            return;
        }

        if ( !array_key_exists($eventData['type'], $this->subscribedTopics)) {
            qlog("The {$eventData['type']} event was pushed but no one listened.", $eventData['log']);

            return;
        }

        $topic = $this->subscribedTopics[$eventData['type']];
        $topic->broadcast($eventData['payload']);
        qlog("The {$eventData['type']} event has been pushed to at least 1 client.", $eventData['log']);
    }
}