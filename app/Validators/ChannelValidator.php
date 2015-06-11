<?php

namespace Pushman\Validators;

use Pushman\Channel;
use Pushman\Exceptions\InvalidChannelException;
use Pushman\Interfaces\EventObject;
use Pushman\Interfaces\Validator;

class ChannelValidator implements Validator
{
    protected $event;

    /**
     * @param EventObject $event
     */
    public function __construct(EventObject $event)
    {
        $this->event = $event;
    }

    public function validate()
    {
        $channels = [];
        $site = $this->event->getSite();

        foreach ($this->event->getChannels() as $channel) {
            $validChannel = Channel::where('name', $channel)->where('site_id', $site->id)->first();
            if (!$validChannel) {
                throw new InvalidChannelException('A channel within this array does not exist.');
            }

            $channels[] = $validChannel;
        }

        $this->event->setChannels($channels);
    }
}
