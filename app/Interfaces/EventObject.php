<?php

namespace Pushman\Interfaces;

use Pushman\Site;

interface EventObject
{
    /**
     * Return the name of the event.
     *
     * @return mixed
     */
    public function getName();

    /**
     * Return an array of channels the event will broadcast to.
     *
     * @return mixed
     */
    public function getChannels();

    /**
     * Return the private key of that site.
     *
     * @return mixed
     */
    public function getPrivateKey();

    /**
     * Return the JSON String Payload.
     *
     * @return mixed
     */
    public function getPayload();

    /**
     * Set the name of the event.
     *
     * @param $name
     *
     * @return mixed
     */
    public function setName($name);

    /**
     * Set an array of channels the event will broadcast to.
     *
     * @param array $channels
     *
     * @return mixed
     */
    public function setChannels($channels = []);

    /**
     * Set the payload JSON string.
     *
     * @param $payload
     *
     * @return mixed
     */
    public function setPayload($payload);

    /**
     * Grabs the site this event lives on.
     *
     * @return mixed
     */
    public function getSite();

    /**
     * Sets the site this event lives on.
     *
     * @param Site $site
     *
     * @return mixed
     */
    public function setSite(Site $site);

    /**
     * Returns the presentable array of data held.
     *
     * @return mixed
     */
    public function present();

    /**
     * Returns a JSON string to send through ZeroMQ.
     *
     * @return mixed
     */
    public function prepare();
}
