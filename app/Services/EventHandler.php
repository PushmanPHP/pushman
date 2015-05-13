<?php namespace Pushman\Services;

use Exception;
use Pushman\Exceptions\InvalidRequestException;
use Pushman\Log;
use Pushman\Site;

class EventHandler {

    public function handle($private, $type, $payload, $log = true)
    {
        try {
            $site = $this->validatePrivateKey($private);
            $event_type = $this->validateEventType($type);
            $payload = $this->validatePayload($payload);
            $raw_event_name = $event_type;
            $event_type = $event_type . '.' . $site->public;

            $payload = json_decode($payload, true);
            $body = [
                'private' => $site->private,
                'type'    => $event_type,
                'log'     => $log,
                'payload' => $payload
            ];
            $body = json_encode($body);

            if ($log) {
                Log::create([
                    'site_id'    => $site->id,
                    'event_name' => $raw_event_name,
                    'payload'    => json_encode($payload)
                ]);
            }

            $port = env('PUSHMAN_INTERNAL', 5555);

            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pushman');
            $socket->connect("tcp://localhost:" . $port);

            $socket->send($body);

            return ['status' => 'success', 'message' => 'Event has been pushed'];
        } catch (Exception $ex) {
            $response = ['status' => 'error', 'message' => $ex->getMessage()];

            return $response;
        }
    }

    private function validatePrivateKey($privateKey)
    {
        $site = Site::wherePrivate($privateKey)->first();
        if ( !$site) {
            throw new InvalidRequestException('No private key found.');
        }

        return $site;
    }

    private function validateEventType($type)
    {
        if (empty($type)) {
            throw new InvalidRequestException('You must provide an event name.');
        }

        return $type;
    }

    private function validatePayload($payload)
    {
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

        return $payload;
    }
} 