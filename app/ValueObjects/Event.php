<?php

namespace Pushman\ValueObjects;

use Carbon\Carbon;
use Exception;
use Pushman\Channel;
use Pushman\Exceptions\InvalidChannelException;
use Pushman\Interfaces\EventObject;
use Pushman\Interfaces\Validator;
use Pushman\Site;

class Event implements EventObject
{
    /**
     * @var
     */
    private $private;
    /**
     * @var
     */
    private $channels;
    /**
     * @var
     */
    private $event;
    /**
     * @var
     */
    private $payload;
    /**
     * @var
     */
    private $site;

    /**
     * The validators to compare this against.
     * These are in a specific order!
     *
     * @var array
     */
    private $validators = [
        \Pushman\Validators\DemoValidator::class,
        \Pushman\Validators\SiteValidator::class,
        \Pushman\Validators\ChannelValidator::class,
        \Pushman\Validators\PayloadValidator::class,
        \Pushman\Validators\LogValidator::class,
    ];

    /**
     * Constructor for the Event Value Object.
     *
     * @param $private
     * @param $channels
     * @param $event
     * @param $payload
     */
    public function __construct($private, $channels, $event, $payload)
    {
        $this->private = $private;
        $this->event = $event;
        $this->payload = $payload;
        $this->processChannels($channels);
    }

    /**
     * Validates the event object.
     */
    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validation_instance = app($validator, [$this]);
            if (!$validation_instance instanceof Validator) {
                throw new Exception('Invalid Validator given');
            }

            $validation_instance->validate();
        }
    }

    /**
     * Return the name of the event.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->event;
    }

    /**
     * Return an array of channels the event will broadcast to.
     *
     * @return mixed
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * Return the private key of that site.
     *
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->private;
    }

    /**
     * Return the JSON String Payload.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set the name of the event.
     *
     * @param $name
     *
     * @return mixed
     */
    public function setName($name)
    {
        $this->event = $name;
    }

    /**
     * Set an array of channels the event will broadcast to.
     *
     * @param array $channels
     *
     * @return mixed
     */
    public function setChannels($channels = ['public'])
    {
        $this->processChannels($channels);
    }

    /**
     * Set the payload JSON string.
     *
     * @param $payload
     *
     * @return mixed
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Processes channels to prevent errors.
     *
     * @param $channels
     */
    private function processChannels($channels)
    {
        if (empty($channels) or is_null($channels)) {
            $this->channels = ['public'];

            return;
        }

        if (is_array($channels)) {
            foreach ($channels as $channel) {
                if (!$channel instanceof Channel) {
                    throw new InvalidChannelException('If given an array of channels, must be of type Pushman\Channel');
                }
            }

            $this->channels = $channels;

            return;
        }

        if (!isJson($channels)) {
            $this->channels = [$channels];

            return;
        }

        $this->channels = json_decode($channels, true);
    }

    /**
     * Grabs the site this event lives on.
     *
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Sets the site this event lives on.
     *
     * @param Site $site
     *
     * @return mixed
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Returns the presentable array of data held.
     *
     * @return mixed
     */
    public function present()
    {
        return [
            'event'     => $this->getName(),
            'channels'  => (array) $this->getChannels(),
            'site'      => $this->getSite()->name,
            'timestamp' => Carbon::now(),
            'payload'   => json_decode($this->getPayload(), true),
        ];
    }

    /**
     * Returns a JSON string to send through ZeroMQ.
     *
     * @return mixed
     */
    public function prepare()
    {
        $channel_ids = [];
        foreach ($this->channels as $channel) {
            $channel_ids[] = $channel->id;
        }
        $channel_ids = array_unique($channel_ids);

        return json_encode([
            'event'    => $this->getName(),
            'payload'  => json_decode($this->getPayload(), true),
            'channels' => $channel_ids,
        ]);
    }
}
