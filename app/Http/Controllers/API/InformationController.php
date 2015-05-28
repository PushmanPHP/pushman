<?php namespace Pushman\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Pushman\Channel;
use Pushman\Http\Controllers\Controller;
use Pushman\Site;
use Validator;

class InformationController extends Controller {

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
} 