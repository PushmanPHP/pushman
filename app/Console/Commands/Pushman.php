<?php namespace Pushman\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Pushman\Services\PushmanHandler;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as EventLoopFactory;
use React\Socket\Server;
use React\ZMQ\Context;

class Pushman extends Command implements SelfHandling {

    protected $description = "Runs the Pushman server.";

    protected $name = "pushman:run";

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $loop = EventLoopFactory::create();
        $pusher = new PushmanHandler();

        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on('message', [$pusher, 'handleEvent']);

        $webSock = new Server($loop);
        $webSock->listen(8080, '0.0.0.0');
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}
