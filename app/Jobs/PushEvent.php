<?php

namespace Pushman\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Pushman\Interfaces\EventObject;

class PushEvent extends Job implements SelfHandling
{
    /**
     * @var EventObject
     */
    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EventObject $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->event->prepare();

        $port = env('PUSHMAN_INTERNAL', 5555);
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pushman');
        $socket->connect('tcp://localhost:'.$port);
        $socket->send($data);
    }
}
