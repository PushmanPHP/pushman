<?php namespace Pushman\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Pushman\Channel;
use Pushman\Services\PushEvent;
use Pushman\Site;
use Validator;

class APIController extends Controller {

    public function push(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'private' => 'required|size:60',
                'channel' => 'string|min:3',
                'event'   => 'required|string|min:3',
                'payload' => 'string'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to validate input.',
                'messages' => $validator->messages()
            ]);
        }

        if (is_null($request->channel)) {
            $channel = 'public';
        } else {
            $channel = $request->channel;
        }

        $event = (new PushEvent())->handle($request->private, $request->event, $channel, $request->payload);

        return response()->json($event);
    }

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
}