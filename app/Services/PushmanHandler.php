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
            echo("{$conn->resourceId} tried to connect on token {$token} but failed.\n");
            $conn->close();
        } else {
            $this->clients->attach($conn);
            echo("New connection! ({$conn->resourceId}) with token ID: {$token}, site: {$site->url}\n");
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
        echo("Connection {$conn->resourceId} has disconnected.\n");
        foreach ($this->subscribedTopics as $type => $topic) {
            $topic->remove($conn);
            echo("Checking {$topic} for broadcast requirement.\n");
            $subCount = count($topic);
            echo("{$topic} still has {$subCount} subscribers.\n");
            if (count($topic) == 0) {
                echo("{$topic} topic doesn't have any more subs. Removing it.\n");
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
        echo("An error occured?\n");
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
        echo("{$conn->resourceId} has tried to call topic {$topic->getId()}\n");
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
        echo("{$conn->resourceId} has subscribed to topic {$topic_id}\n");
    }

    /**
     * A request to unsubscribe from a topic has been made
     * @param ConnectionInterface $conn
     * @param string|Topic        $topic The topic to unsubscribe from
     */
    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        echo("{$conn->resourceId} has unsubscribed from topic {$topic->getId()}\n");
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
        echo("Client {$conn->resourceId} is trying to publish something?\n");
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
            echo("The {$eventData['type']} event was pushed but no one listened.\n");

            return;
        }

        echo("The {$eventData['type']} event has been pushed to at least 1 client.\n");

        $topic = $this->subscribedTopics[$eventData['type']];

        $topic->broadcast($eventData['payload']);
    }
}