<?php namespace Pushman\Http\Controllers\v0;

use Exception;
use Illuminate\Http\Request;
use Pushman\Exceptions\InvalidRequestException;
use Pushman\Http\Controllers\Controller;
use Pushman\Http\Requests;
use Pushman\Log;
use Pushman\Site;

class EventController extends Controller {

    public function push(Request $request)
    {
        try {
            $site = $this->validatePrivateKey($request->private);
            $event_type = $this->validateEventType($request->type);
            $payload = $this->validatePayload($request->payload);
            $raw_event_name = $event_type;
            $event_type = $event_type . '.' . $site->public;

            $payload = json_decode($payload, true);
            $body = [
                'private' => $site->private,
                'type'    => $event_type,
                'payload' => $payload
            ];
            $body = json_encode($body);

            $log = Log::create([
                'site_id'    => $site->id,
                'event_name' => $raw_event_name,
                'payload'    => json_encode($payload)
            ]);

            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pushman');
            $socket->connect("tcp://localhost:5555");

            $socket->send($body);

            return response()->json(['status' => 'success', 'message' => 'Event has been pushed']);
        } catch (Exception $ex) {
            $payload = ['status' => 'error', 'message' => $ex->getMessage()];

            return response()->json($payload);
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
        $payload = str_replace(' ', '', $payload); // remove spaces

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
