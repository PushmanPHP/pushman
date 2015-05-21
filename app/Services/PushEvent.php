<?php namespace Pushman\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Pushman\Channel;
use Pushman\Exceptions\InvalidPayloadException;
use Pushman\Site;

class PushEvent {

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
            return ['status' => 'error', 'message' => 'This payload is not JSON!'];
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