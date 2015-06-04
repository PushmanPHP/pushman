<?php namespace Pushman\Services;

use Carbon\Carbon;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Support\Collection;
use Pushman\Channel;
use Pushman\Exceptions\InvalidPayloadException;
use Pushman\Site;

class PushEvent {

    /**
     * Handles an incoming event from any source
     * Pushes it out to the PushmanHandler via ZeroMQ
     *
     * @param        $private
     * @param        $event
     * @param array  $channels
     * @param string $payload
     * @param bool   $logRequest
     * @return array
     */
    public function handle($private, $event, $channels = [], $payload = '', $logRequest = true)
    {
        $site = Site::where('private', $private)->first();
        if ( !$site) {
            return ['status' => 'error', 'message' => 'Unable to link private key to site.'];
        }

        if (is_null($channels)) {
            $channels = ['public'];
        }

        $arrChannels = new Collection();
        foreach ($channels as $strChannel) {
            $channel = Channel::where('name', $strChannel)->where('site_id', $site->id)->first();
            if ( !$channel) {
                return ['status' => 'error', 'message' => 'Channel does not exist.'];
            }
            $arrChannels[] = $channel;
        }

        try {
            $payload = $this->validatePayload($payload);
        } catch (InvalidPayloadException $ex) {
            return ['status' => 'error', 'message' => 'This payload is not valid JSON!'];
        }

        $payload = json_decode($payload, true);

        $pushmanPayload = [
            'private'  => $private,
            'event'    => $event,
            'channels' => $arrChannels->toJson(),
            'payload'  => $payload,
            'log'      => $logRequest
        ];
        $pushmanPayload = json_encode($pushmanPayload);

        if ($logRequest) {
            $channel->events_fired += 1;
            $channel->save();
        }

        $port = env('PUSHMAN_INTERNAL', 5555);
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pushman');
        $socket->connect("tcp://localhost:" . $port);
        $socket->send($pushmanPayload);

        return [
            'status'    => 'success',
            'message'   => 'Event pushed successfully.',
            'event'     => $event,
            'channels'  => $arrChannels,
            'site'      => $site->name,
            'timestamp' => Carbon::now(),
            'payload'   => $payload
        ];
    }

    /**
     * Validates a payload.
     * Makes sure its all UTF-8 characters
     * Makes sure there are no odd html characters.
     * Stops <script> tags.
     *
     * @param $payload
     * @return mixed|string
     * @throws \Pushman\Exceptions\InvalidPayloadException
     */
    private function validatePayload($payload)
    {
        if (empty($payload)) {
            return false;
        }

        $payload = str_replace("\r", '', $payload); // remove carriage returns
        $payload = str_replace("\n", '', $payload); // remove new lines
        $payload = str_replace("\t", '', $payload); // remove tabs

        $payloadCharacters = str_split($payload);
        $characterArray = [];
        foreach ($payloadCharacters as $key => $character) {
            if (mb_check_encoding($character, "UTF-8")) {
                $characterArray[] = $character;
            }
        }
        $payload = implode('', $characterArray);

        $payload = BInput::clean($payload);

        if ( !$this->isJson($payload) OR $this->containsScripts($payload)) {
            throw new InvalidPayloadException('Bad JSON');
        }

        return $payload;
    }

    /**
     * Detects if a string is JSON.
     *
     * @param $string
     * @return bool
     */
    private function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Literally checks for <script>.
     *
     * @param $payload
     * @return bool
     */
    private function containsScripts($payload)
    {
        if (str_contains($payload, "<script>")) {
            return true;
        }

        if (str_contains($payload, 'javascript:')) {
            return true;
        }

        return false;
    }
}