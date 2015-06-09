<?php namespace Pushman\Http\Controllers\API;

use Illuminate\Http\Request;
use Pushman\Exceptions\InvalidRequestException;
use Pushman\Http\Controllers\Controller;
use Pushman\Services\PushEvent;
use Pushman\Site;
use Validator;

class EventController extends Controller
{
    /**
     * Handles in the incoming push request for Pushman.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function push(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'private'  => 'required|size:60',
                'channels' => 'string|min:3',
                'event'    => 'required|string|min:3',
                'payload'  => 'string'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to validate input.',
                'messages' => $validator->messages()
            ]);
        }

        try {
            $channels = $this->getChannels($request->channels);
        } catch (InvalidRequestException $ex) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to parse channels.',
                'messages' => $ex->getMessage()
            ]);
        }

        if ($request->private === 'this_is_a_60_char_string_that_looks_like_a_valid_private_key') {
            $private = Site::where('name', 'demo')->where('url', 'http://pushman.dfl.mn')->first()->private;
        } else {
            $private = $request->private;
        }

        $event = (new PushEvent())->handle($private, $request->event, $channels, $request->payload);

        return response()->json($event);
    }

    /**
     * Decodes a JSON array of channels.
     *
     * @param $channels
     * @return array|mixed
     */
    private function getChannels($channels)
    {
        if (!isJson($channels)) {
            return [$channels];
        }

        return json_decode($channels, true);
    }
}
