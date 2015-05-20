@extends('app')

@section('container')

<!-- ****************************************************************************************** -->
<!-- Scroll down to the Javascript at the bottom of the page if you want to see how this works! -->
<!-- ****************************************************************************************** -->

<div class="background bottom40">
		<div class="container">

			<h1 class="bottom40">Demo</h1>

			<div class="bottom20">
				<h6>Check out the source of this page!</h6>
			</div>

		</div>
	</div>

	<div class="container">

		<div class="row">
			<div class="col-lg-6 right-border-grey">
				<div class="bottom20">
					<h4>1. Setup catching the event</h4>
					<p class="about_font">What event should we listen for?</p>
					{!! Form::open(['id' => 'listenForm', 'class'=>'form form-inline']) !!}
						<!-- listen_event_name Field -->
						<div class="form-group">
							{!! Form::label('listen_event_name', 'Event: ', ['class' => 'control-label']) !!}
							{!! Form::text('listen_event_name', null, ['class' => 'form-control']) !!}
							{!! $errors->first('listen_event_name', '<p class="help-block">:message</p>') !!}
						</div>
						<!-- Submit Button -->
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block">Listen</button>
						</div>
					{!! Form::close() !!}
				</div>

				<div class="row bottom40">
					<div class="col-lg-12">
						<h4>Output Log</h4>
						<pre><code class="javascript" id="divLog"></code></pre>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-4">
						<a id="btnConnect" href="#" class="btn btn-block btn-success btn-xs">Connect</a>	
					</div>
					<div class="col-lg-4">
						<a id="btnDisconnect" href="#" class="btn btn-block btn-danger btn-xs">Disconnect</a>
					</div>
					<div class="col-lg-4 text-right">
						<strong>Status: </strong> <span id="connectionState">Disconnected</span>
					</div>
				</div>
			</div>
			<div class="col-lg-6 left-border-grey">
				<h4>2. Push an Event</h4>
				{!! Form::open(['id' => 'requestForm', 'class' => 'form-horizontal']) !!}
				
					<!-- Channel Field -->
					<div class="form-group">
						{!! Form::label('channel', 'Channel', ['class' => 'control-label col-sm-2']) !!}
						<div class="col-sm-10">
							{!! Form::select('channel', $site->getChannelNames(), 'public', ['class' => 'form-control']) !!}
							{!! $errors->first('channel', '<p class="help-block">:message</p>') !!}
						</div>
					</div>

					<!-- event Field -->
					<div class="form-group">
						{!! Form::label('event', 'Event', ['class' => 'control-label col-sm-2']) !!}
						<div class="col-sm-10">
							{!! Form::text('event', null, ['class' => 'form-control', 'placeholder' => 'event_name']) !!}
							{!! $errors->first('event', '<p class="help-block">:message</p>') !!}
						</div>
					</div>

					<!-- payload Field -->
					<div class="form-group">
						{!! Form::label('payload', 'Payload', ['class' => 'control-label col-sm-2']) !!}
						<div class="col-sm-10">
							{!! Form::textarea('payload', "{\n&nbsp;&nbsp;&nbsp;&nbsp;\"foo\": \"bar\"\n}", ['rows' => '3', 'class' => 'form-control']) !!}
							{!! $errors->first('payload', '<p class="help-block">:message</p>') !!}
						</div>
					</div>

					<!-- Submit Button -->
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<button type="submit" class="btn btn-success btn-block">Push Event</button>
						</div>
					</div>
					
				{!! Form::close() !!}
				
				<div class="row">
					<div class="col-lg-12">
						<h4>Returned Response</h4>
						<pre><code class="javascript" id="response"></code></pre>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<hr>
			</div>
		</div>

		<div class="row bottom20">
			<div class="col-lg-12 meaty_font">
				<h4>Stuck using the Demo?</h4>
				<p>There are two parts to how this works. 1. Catching the Event, where you emulate your client, and 2. Push an Event, where you emulate sending a HTTP POST request from the server to Pushman.</p>

				<ul>
					<li>Enter an Event Name on the left. Let's use <code>kittens_are_cute</code>!</li>
					<li>Click 'Listen'. This means your client is listening for the <code>kittens_are_cute</code> event.</li>
					<li>Move to Step 2, push an event out on the public channel called <code>kittens_are_cute</code>.</li>
					<li>Change the payload if you want!</li>
					<li>As you hit 'Push Event', watch the Server respond, is status set to <code>success</code>?</li>
					<li>Watch the <em>Output Log</em> on the left to see your client grab your event.</li>
				</ul>

				<p>The client never pinged the server and asked for the event! The server pushed it down using a Web Socket!</p>

				<p>You can also setup your own HTTP POST request to test this. Send a HTTP POST request to <code>http://pushman.dfl.mn/api/push</code> with some POST headers (as shown below) and watch as the Output Log picks it up.</p>
				<p>Use the private key: <code>this_is_a_60_char_string_that_looks_like_a_valid_private_key</code>.</p>
				<p><a href="/documentation">Check out the Documentation</a> on the fields required to push your own POST request.</p>
			</div>
		</div>

		<div class="row bottom40">
			<div class="col-lg-12">
				<h5>Got cURL? Paste this into your Console</h5>
				<pre><code class="bash">curl -X POST -F "private=this_is_a_60_char_string_that_looks_like_a_valid_private_key" -F "event=kittens_are_cute" http://pushman.dfl.mn/api/push</code></pre>
			</div>
		</div>

	</div>

</div>

@endsection

@section('javascript')
@parent
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/highlight.min.js"></script>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script>
/*
 * Howdy! You should read this to see how the demo works.
 * Don't be afraid to ping me on Twitter @Duffleman 
 *
 */

// Holds the connections wer are listening to.
// You don't need to do this, Pushman already handles clients trying to subscribe twice.
var listening = [];
// Holds our connection variable in the global scope.
var conn;

$(document).ready(function() {
	// Check if we are connected every second.
	// This only updates the Status: text.
	setInterval(checkConnection, 1000);

	// Connect the Pushman to begin.
	connect();

	// Handle the PUSH event form.
	$('#requestForm').on('submit', function(event) {
		event.preventDefault();
		$('#response').html('');

		// Grab the Form data.
		var channel = $('#channel').val();
		var event = $('#event').val();
		var payload = $('#payload').val();
		// Demo key valid only for this demo.
		var private_key = 'this_is_a_60_char_string_that_looks_like_a_valid_private_key';

		// Emulates you pushing an event to us via a HTTP POST request.
		$.post('/api/push', { channel: channel, event: event, payload: payload, private: private_key}, function(data) {
			// It auto parses the JSON into an object. Lets turn it back into a string here.
			var str = JSON.stringify(data, null, 2);
			// Put it into the response div box.
			$('#response').html(str);
			// Don't worry about this bit, this just makes it look good on the UI.
			hljs.highlightBlock(document.getElementById('response'));
		});
	});

	// Catch Connect and Disconnect buttons
	$('#btnConnect').on('click', function(event) {
		event.preventDefault();
		// Connect to Pushman
		connect();
	});
	$('#btnDisconnect').on('click', function(event) {
		event.preventDefault();
		// Disconnect from Pushman
		disconnect();
	});

	// Handle the CATCH event form.
	$('#listenForm').on('submit', function(event) {
		event.preventDefault();
		// Grab Form data
		var event_name = $('#listen_event_name').val();

		// Are they already listening to the event?
		// If so, let's not bother Pushman, just tell them.
		if(inArray(event_name, listening)) {
			divLog('You are already listening to that event.');
			return false;
		} else {
			listening.push(event_name);
			// Assuming they are connected, lets subscribe to the event on the public channel.
			if(checkConnection() == true) {
				conn.subscribe(event_name, function(topic, data) {
					// This is what happens when we catch the event!
					divLog("Caught Event! "+event_name+" was caught with " + JSON.stringify(data));
				});
				divLog('Started listening to ' + event_name);
			} else {
				divLog("Unable to listen to event. Connect to Web Socket first!");
			}
		}
	});
});

/*
 * This function checks to see if there is an element within an array for Javascript.
 */
function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

// This function handles connecting to Pushman via a WebSocket.
// No point connecting if you're already connected so it'll always try to disconnect first.
function connect() {
	disconnect();

	// This token changes every 60 minutes automatically.
	// We use Serverside helpers so we never have to change the code.
	conn = new ab.Session('ws://{{ PushPrep::getHost() }}:{{ PushPrep::getPort() }}?token={{ PushPrep::getDemo() }}',
		function() {
			divLog('Connected to Pushman via Web Socket.');
		},
		function() {
			divLog('Disconnected from Pushman!');
		},
		{'skipSubprotocolCheck': true}
	);
}

// This function handles disconnecting from Pushman.
// Try and catch is needed incase the user tries to disconnect before the connection is orignally established.
function disconnect()
{
	try {
		conn.close();
	} catch(err) {
		// do nothing.
	}
	listening = [];
}

// This checks to see if we're currently connected to pushman.
function checkConnection()
{
	var div = $('#connectionState');
	var state = false;
	try {
		state = conn._websocket_connected;
	} catch(ex) {
		div.html('Disconnected');
		return false;
	}
	if(state == true) {
		div.html('Connected');
		return true;
	} else {
		div.html('Disconnected');
		return false;
	}
}


// This is an easy function to push logs to both the console and the div "Output Log".
// It auto appends a H:i:s timestamp at the begining.
function divLog(text)
{
	var d = new Date();
	var time = pad(d.getHours().toString(),2,0,STR_PAD_LEFT) + ":" + pad(d.getMinutes().toString(),2,0,STR_PAD_LEFT) + ":" + pad(d.getSeconds().toString(),2,0,STR_PAD_LEFT);

	var text = time + " - " + text;

	var div = $('#divLog');
	console.log(text);
	div.append(text + "\n");
}

var STR_PAD_LEFT = 1;
var STR_PAD_RIGHT = 2;
var STR_PAD_BOTH = 3;

// This is used purely for the timestamp
// It pads 0s for formatting for the Hour, Minute, and Second.
function pad(str, len, pad, dir) {
	if (typeof(len) == "undefined") { var len = 0; }
	if (typeof(pad) == "undefined") { var pad = '0'; }
	if (typeof(dir) == "undefined") { var dir = STR_PAD_RIGHT; }

	if (len + 1 >= str.length) {
		switch (dir){
			case STR_PAD_LEFT:
				str = Array(len + 1 - str.length).join(pad) + str;
			break;

			case STR_PAD_BOTH:
				var right = Math.ceil((padlen = len - str.length) / 2);
				var left = padlen - right;
				str = Array(left+1).join(pad) + str + Array(right+1).join(pad);
			break;

			default:
				str = str + Array(len + 1 - str.length).join(pad);
			break;
		}
	}
	return str;
}
</script>
@endsection