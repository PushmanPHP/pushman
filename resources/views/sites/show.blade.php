@extends('app')

@section('content')

	<div class="row bottom20">
		<div class="col-lg-12">
			<h2>{{ str_limit($site->name,25) }}</h2>
			<h5>{{ $site->url }}</h5>
		</div>
	</div>

	<div class="row animated removed" id="rowKeys">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<h3 class="panel-title pull-right" id="btnHideKeys"><a href="#">&times;</a></h3>
					<h3 class="panel-title">Site Keys</h3>
				</div>
				<table class="table">
					<tbody>
						<tr>
							<th>Public</th>
							<td><code>{{ Pushman\Repositories\ChannelRepository::getPublic($site)->public }}</code></td>
						</tr>
						<tr>
							<th>Private</th>
							<td><code>{{ $site->private }}</code></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2 id="listener_count">{{ $site->current_users() }}</h2>
					<h6>{{ str_plural('Listener', $site->current_users()) }}</h6>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2>{{$site->channels->count()}}</h2>
					<h6>{{ str_plural('Channel', $site->channels->count()) }}</h6>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2>{{ $site->max_connections() }}</h2>
					<h6>Max Connections</h6>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2 id="eventCount">{{ $site->events_fired() }}</h2>
					<h6>{{ str_plural('Event', $site->events_fired()) }} Fired</h6>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3">
			<a href="/sites/{{$site->id}}/channels" class="btn btn-primary btn-block">Channel Management</a>
		</div>
		<div class="col-lg-3">
			<a href="#" id="btnRevealKeys" class="btn btn-info btn-block">Reveal Keys</a>
		</div>
		<div class="col-lg-3">
			<a href="/sites/{{$site->id}}/regenerate" class="btn btn-warning btn-block swal">Regenerate Token</a>
		</div>
		<div class="col-lg-3">
			<a href="/sites/{{$site->id}}/delete" class="btn btn-danger btn-block swal">Delete Site</a>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-lg-12">
			<h4>Push Event</h4>
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

@endsection

@section('javascript')
@parent
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script>
$(document).ready(function() {
	$('#btnRevealKeys').on('click', function(event) {
		event.preventDefault();
		var keys = $('#rowKeys');

		keys.addClass('readded');
	});
	$('#btnHideKeys').on('click', function(event) {
		event.preventDefault();
		var keys = $('#rowKeys');

		keys.removeClass('readded');
	});
	$('#requestForm').on('submit', function(event) {
		event.preventDefault();
		$('#response').html('');

		var event_count_dom = $('#eventCount');
		var event_count = event_count_dom.html();
	    event_count = parseInt(event_count);

		var channels = $('#channel').val();
		var event = $('#event').val();
		var payload = $('#payload').val();
		var private_key = '{{$site->private}}';

		$.post('/api/push', { channels: channels, event: event, payload: payload, private: private_key}, function(data) {
			var str = JSON.stringify(data, null, 2);
			$('#response').html(str);
			hljs.highlightBlock(document.getElementById('response'));

			if(data.status == 'success') {
                event_count += 1;
                event_count_dom.html(event_count);
			}
		});
	});
});
</script>
@endsection