<?php namespace Pushman\Validators;

use Pushman\Interfaces\EventObject;
use Pushman\Interfaces\Validator;

class LogValidator implements Validator
{

    /**
     * @var EventObject
     */
    private $event;

    public function __construct(EventObject $event)
    {
        $this->event = $event;
    }

    public function validate()
    {
        $channels = $this->event->getChannels();

        foreach ($channels as $channel) {
            $channel->events_fired += 1;
            $channel->save();
        }
    }
}