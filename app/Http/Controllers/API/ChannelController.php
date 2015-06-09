<?php namespace Pushman\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Pushman\Channel;
use Pushman\Exceptions\InvalidChannelException;
use Pushman\Http\Controllers\Controller;
use Pushman\Repositories\ChannelRepository;
use Pushman\Repositories\SiteRepository;
use Validator;

class ChannelController extends Controller
{
    /**
     * Create a set of channels
     *
     * @param \Illuminate\Http\Request $request
     * @return \Pushman\Channel|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'private'   => 'required|size:60',
                'channel'   => 'required|string|min:3',
                'refreshes' => 'in:yes,no'
            ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to validate input',
                'messages' => $validator->messages()
            ]);
        }

        if (isJson($request->channel)) {
            $handleMultiple = true;
            $channels = json_decode($request->channel, true);
        } else {
            $handleMultiple = false;
            $channels = [$request->channel];
        }

        $site = SiteRepository::find($request->private);
        if (!$site) {
            return response()->json([
                'status' => 'error', 'message' => 'Unable to validate private key.'
            ]);
        }

        try {
            foreach ($channels as $channel) {
                ChannelRepository::validateName($channel, $site);
            }
        } catch (InvalidChannelException $ex) {
            return response()->json([
                'status' => 'error', 'message' => 'Unable to validate channel name. Possible duplicate?'
            ]);
        }

        $dbChannels = new Collection();
        foreach ($channels as $channel) {
            $refreshes = $request->refreshes ? $request->refreshes : 'no';
            $max = $request->max ? $request->max : 3;

            if (!ChannelRepository::validateMaxConnections($max)) {
                return response()->json([
                    'status' => 'error', 'message' => 'That is not a valid amount of max connections.'#
                ]);
            }

            $dbChannels->push(ChannelRepository::build($channel, $refreshes, $max, $site));
        }

        return $dbChannels;
    }

    /**
     * Destroy a set of channels
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'private' => 'required|size:60',
                'channel' => 'required|string|min:3',
            ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error', 'message' => 'Unable to validate input',
                'messages' => $validator->messages()
            ]);
        }

        $site = SiteRepository::find($request->private);
        if (!$site) {
            return response()->json([
                'status' => 'error', 'message' => 'Unable to validate the private key.'
            ]);
        }

        if (isJson($request->channel)) {
            $handleMultiple = true;
            $channels = json_decode($request->channel, true);
        } else {
            $handleMultiple = false;
            $channels = [$request->channel];
        }

        $deleted_entries = [];
        $failed_entries = [];
        foreach ($channels as $channel) {
            $dbChannel = Channel::where('name', $channel)
                ->where('site_id', $site->id)
                ->where('name', '!=', 'public')
                ->first();
            if ($dbChannel) {
                $dbChannel->delete();
                $deleted_entries[] = $dbChannel->name;
            } else {
                $failed_entries[] = $channel;
            }
        }

        return response()->json([
            'status'    => 'success',
            'message'   => '',
            'deleted'   => implode(',', $deleted_entries),
            'failed_on' => implode(',', $failed_entries)
        ]);
    }
}
