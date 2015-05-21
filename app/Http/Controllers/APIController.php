<?php namespace Pushman\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Pushman\Channel;
use Pushman\Exceptions\InvalidRequestException;
use Pushman\Services\PushEvent;
use Pushman\Site;
use Validator;

class APIController extends Controller {

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
     * returns information on a single channel.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function channel(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'private' => 'required|size:60',
                'channel' => 'required|min:3',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to validate input.',
                'messages' => $validator->messages()
            ]);
        }

        $site = Site::where('private', $request->private)->first();
        if ( !$site) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to link private key to site.'
            ]);
        }

        $channel = Channel::where('name', $request->channel)->where('site_id', $site->id)->first();
        if ( !$channel) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to find that channel name.'
            ]);
        }

        $channel = $channel->toArray();
        $expiry = Carbon::now();
        $expiry->hour = $expiry->addHour()->format('H');
        $expiry->minute = 0;
        $expiry->second = 0;
        $channel['token_expires'] = $expiry;

        return $channel;
    }

    /**
     * Returns a full list of channels associated with a site.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function channels(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'private' => 'required|size:60'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to validate input.',
                'messages' => $validator->messages()
            ]);
        }

        $site = Site::where('private', $request->private)->first();
        if ( !$site) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to link private key to site.'
            ]);
        }

        return $site->channels;
    }

    /**
     * Decodes a JSON array of channels.
     *
     * @param $channels
     * @return array|mixed
     */
    private function getChannels($channels)
    {
        if ( !$this->isJson($channels)) {
            return [$channels];
        }

        return json_decode($channels, true);
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
}