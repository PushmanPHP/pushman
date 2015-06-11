<?php

namespace Pushman\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Pushman\Http\Controllers\Controller;
use Pushman\ValueObjects\Event;
use Validator;

class EventController extends Controller
{
    /**
     * Handles in the incoming push request for Pushman.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function push(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'private'  => 'required|size:60',
            'channels' => 'string|min:3',
            'event'    => 'required|string|min:3',
            'payload'  => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Unable to validate input.',
                'messages' => $validator->messages(),
            ]);
        }

        try {
            $event_vo = new Event($request->private, $request->channels, $request->event, $request->payload);
            $event_vo->validate();

            $this->dispatch(new \Pushman\Jobs\PushEvent($event_vo));
        } catch (Exception $exc) {
            return response()->json([
                'status'  => 'error',
                'message' => $exc->getMessage(),
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Event pushed successfully',
            'event'   => $event_vo->present(),
        ]);
    }
}
