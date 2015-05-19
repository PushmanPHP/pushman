@extends('app')

@section('container')

<div class="cover-about bottom40">
		<div class="container">

			<h2 class="bottom40">Demo</h2>

			<div class="bottom20">
				<h6>Check out the source of this page!</h6>
			</div>

		</div>
	</div>

	<div class="container">

		<div class="row bottom20">
			<div class="col-lg-12">
				<h4>Catch the event</h4>
			</div>
			<div class="col-lg-6">
				<h6>What event should we listen for?</h6>
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
			<div class="col-lg-6">
				<div class="row bottom20">
					<div class="col-lg-3 col-lg-offset-6">
						<a id="btnConnect" href="#" class="btn btn-block btn-success">Connect</a>	
					</div>
					<div class="col-lg-3">
						<a id="btnDisconnect" href="#" class="btn btn-block btn-danger">Disconnect</a>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-lg-offset-6 text-right">
						<strong>Status: </strong> <span id="connectionState">Disconnected</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<h4>Output Log</h4>
				<pre><code class="javascript" id="divLog"></code></pre>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<hr>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<h4>Push an Event</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
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
			</div>
			<div class="col-lg-6">
				<pre><code class="javascript" id="response"></code></pre>
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
var listening = [];
var conn;
$(document).ready(function() {
	setInterval(checkConnection, 1000);
	connect();

	// Handle the PUSH event form.
	$('#requestForm').on('submit', function(event) {
		event.preventDefault();
		$('#response').html('');

		var channel = $('#channel').val();
		var event = $('#event').val();
		var payload = $('#payload').val();
		// Demo key valid only for this demo.
		var private_key = 'this_is_a_60_char_string_that_looks_like_a_valid_private_key';

		// Emulates you pushing an event to us via a HTTP POST request.
		$.post('/api/push', { channel: channel, event: event, payload: payload, private: private_key}, function(data) {
			var str = JSON.stringify(data, null, 2);
			$('#response').html(str);
			// Don't worry about this bit, this just makes it look good on the UI.
			hljs.highlightBlock(document.getElementById('response'));
		});
	});

	// Catch Connect and Disconnect buttons
	$('#btnConnect').on('click', function(event) {
		event.preventDefault();
		connect();
	});
	$('#btnDisconnect').on('click', function(event) {
		event.preventDefault();
		disconnect();
	});

	// Handle the CATCH event form.
	$('#listenForm').on('submit', function(event) {
		event.preventDefault();
		var event_name = $('#listen_event_name').val();

		if(inArray(event_name, listening)) {
			divLog('You are already listening to that event.');
			return false;
		} else {
			listening.push(event_name);
			if(checkConnection() == true) {
				conn.subscribe(event_name, function(topic, data) {
					divLog("Caught Event! "+event_name+" was caught with " + JSON.stringify(data));
				});
				divLog('Started listening to ' + event_name);
			} else {
				divLog("Unable to listen to event. Connect to Web Socket first!");
			}
		}
	});
});

function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

function connect() {
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

function disconnect()
{
	conn.close();
	listening = [];
}

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