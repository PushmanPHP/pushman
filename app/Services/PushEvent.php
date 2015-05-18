<?php namespace Pushman\Services;

use Carbon\Carbon;
use Pushman\Channel;
use Pushman\Exceptions\InvalidPayloadException;
use Pushman\Site;

class PushEvent {

    public function handle($private, $event, $channel = 'public', $payload = '', $logRequest = true)
    {
        $site = Site::where('private', $private)->first();
        if ( !$site) {
            return ['status' => 'error', 'message' => 'Unable to link private key to site.'];
        }

        $channel = Channel::where('name', $channel)->where('site_id', $site->id)->first();
        if ( !$channel) {
            return ['status' => 'error', 'message' => 'Channel does not exist.'];
        }

        try {
            $payload = $this->validatePayload($payload);
        } catch (InvalidPayloadException $ex) {
            return ['status' => 'error', 'message' => 'This payload is not JSON!'];
        }

        $payload = json_decode($payload, true);

        $pushmanPayload = [
            'private' => $private,
            'event'   => $event,
            'channel' => $channel,
            'payload' => $payload,
            'log'     => $logRequest
        ];
        $pushmanPayload = json_encode($pushmanPayload);

        if ($logRequest) {
            $channel->events_fired += 1;
            $channel->save();

            $internal = $site->getInternal();
            if ( !is_null($internal)) {
                (new self())->handle($private, 'log', 'pushmaninternal', json_encode([
                    'event'   => $event,
                    'payload' => json_encode($payload)
                ]), false);
            }
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
            'channel'   => $channel->name,
            'site'      => $site->name,
            'timestamp' => Carbon::now(),
            'payload'   => $payload
        ];
    }

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

        if ( !$this->isJson($payload)) {
            throw new InvalidPayloadException('Bad JSON');
        }

        return $payload;
    }

    private function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}