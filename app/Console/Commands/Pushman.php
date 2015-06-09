<?php namespace Pushman\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Pushman\Services\PushmanHandler;
use Pushman\Services\PushmanWampServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as EventLoopFactory;
use React\Socket\Server;
use React\ZMQ\Context;

class Pushman extends Command implements SelfHandling
{
    protected $description = "Runs the Pushman server.";

    protected $name = "pushman:run";

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $port = env('PUSHMAN_INTERNAL', 5555);
        $public = env('PUSHMAN_PORT', 8080);

        $loop = EventLoopFactory::create();
        $pusher = new PushmanHandler();

        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:' . $port);
        $pull->on('message', [$pusher, 'handleEvent']);

        $webSock = new Server($loop);
        $webSock->listen($public, '0.0.0.0');
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new PushmanWampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}
