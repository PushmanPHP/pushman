<?php

namespace Pushman\Http\Controllers\API;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Pushman\Channel;
use Pushman\Exceptions\InvalidChannelException;
use Pushman\Exceptions\InvalidSiteException;
use Pushman\Http\Controllers\Controller;
use Pushman\Site;
use Validator;

class InformationController extends Controller
{
    /**
     * returns information on a single channel.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function channel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'private' => 'required|size:60',
            'channel' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Unable to validate input.',
                'messages' => $validator->messages(),
            ]);
        }

        try {
            $site = $this->getSite($request->private);
            $channel = $this->getChannel($request->channel, $site);
        } catch (Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage(),
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function channels(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'private' => 'required|size:60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Unable to validate input.',
                'messages' => $validator->messages(),
            ]);
        }

        try {
            $site = $this->getSite($request->private);
        } catch (Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage(),
            ]);
        }

        return $site->channels;
    }

    /**
     * Handles the returning of Subscribers to a single channel and their events.
     *
     * @param Request $request
     *
     * @return Collection|\Symfony\Component\HttpFoundation\Response
     */
    public function subscribers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'private' => 'required|size:60',
            'channel' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Unable to validate input.',
                'messages' => $validator->messages(),
            ]);
        }

        try {
            $site = $this->getSite($request->private);
            $channel = $this->getChannel($request->channel, $site);
        } catch (Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage(),
            ]);
        }

        $data = $this->parseSubcribers($channel->subscribers);

        return $data;
    }

    /**
     * Grabs a site if possible.
     *
     * @param $private
     *
     * @throws InvalidSiteException
     *
     * @return mixed
     */
    private function getSite($private)
    {
        $site = Site::where('private', $private)->first();
        if (!$site) {
            throw new InvalidSiteException('Cannot find the site from that private key.');
        }

        return $site;
    }

    /**
     * Grabs a channel if possible.
     *
     * @param      $channel
     * @param Site $site
     *
     * @throws InvalidChannelException
     *
     * @return mixed
     */
    private function getChannel($channel, Site $site)
    {
        $channel = Channel::where('name', $channel)->where('site_id', $site->id)->first();
        if (!$channel) {
            throw new InvalidChannelException('Unable to find that channel name.');
        }

        return $channel;
    }

    /**
     * Parses the subscribers into a nice JSON formatable object.
     *
     * @param $subscribers
     *
     * @return Collection
     */
    private function parseSubcribers($subscribers)
    {
        $subs = new Collection();
        $events = [];

        foreach ($subscribers as $subscriber) {
            $events[$subscriber->resource_id][] = $subscriber->pivot->event;
            $subscriber = $subscriber->toArray();
            $subscriber = $this->unsetUnwantedVariables($subscriber);
            $subscriber['userdata'] = json_decode($subscriber['userdata'], true);
            $subs[$subscriber['resource_id']] = $subscriber;
        }

        foreach ($subs as $key => $sub) {
            $subs[$key] = $this->populateEvents($sub, $events[$key]);
        }

        return $subs;
    }

    /**
     * Strips data that isn't even going to be relevant.
     *
     * @param $subscriber
     *
     * @return mixed
     */
    private function unsetUnwantedVariables($subscriber)
    {
        unset($subscriber['pivot']);
        unset($subscriber['site_id']);
        unset($subscriber['updated_at']);
        unset($subscriber['id']);

        return $subscriber;
    }

    /**
     * Quick merge of two arrays.
     *
     * @param $sub
     * @param $events
     *
     * @return mixed
     */
    private function populateEvents($sub, $events)
    {
        $sub['events'] = $events;

        return $sub;
    }
}
