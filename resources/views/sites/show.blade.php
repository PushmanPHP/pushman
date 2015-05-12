@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $site->name }}</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><a href="#" id="revealKeysLink">Reveal Keys</a></h3>
				</div>
				<div class="panel-body hidden" id="keys">
					<div class="row">
						<div class="col-lg-6">
							<h5 class="no-top">Public Key</h5>
							<code>{{$site->public}}</code>
						</div>
						<div class="col-lg-6">
							<h5 class="no-top">Private Key</h5>
							<code>{{$site->private}}</code>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<h4>Test Event</h4>
			{!! Form::open(['id' => 'requestForm', 'class' => 'form-horizontal', 'url' => '/api/v0/push']) !!}
				
				<!-- type Field -->
				<div class="form-group">
					{!! Form::label('type', 'Event', ['class' => 'control-label col-sm-2']) !!}
					<div class="col-sm-10">
						{!! Form::text('type', null, ['class' => 'form-control', 'placeholder' => 'event.name']) !!}
						{!! $errors->first('type', '<p class="help-block">:message</p>') !!}
					</div>
				</div>

				<!-- Payload Field -->
				<div class="form-group">
					{!! Form::label('payload', 'Payload', ['class' => 'control-label col-sm-2']) !!}
					<div class="col-sm-10">
						{!! Form::textarea('payload', "{\n&nbsp;&nbsp;&nbsp;&nbsp;\"foo\": \"bar\"\n}", ['rows' => 3, 'class' => 'form-control']) !!}
						{!! $errors->first('payload', '<p class="help-block">:message</p>') !!}
					</div>
				</div>

				<input type="hidden" id="private" name="private" value="{{$site->private}}">

				<!-- Submit Button -->
				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-2">
						<button type="submit" class="btn btn-info btn-block">Push Event</button>
					</div>
				</div>

			{!! Form::close() !!}
		</div>
		<div class="col-lg-6">
			<h6>Response</h6>
			<div class="well" id="response"></div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-2 col-lg-offset-10 text-right">
			<h4>Actions</h4>
			<a href="/sites/{{$site->id}}/regen" class="btn btn-warning btn-block">Regen Tokens</a>
			<a href="/sites/{{$site->id}}/delete" class="btn btn-danger btn-block">Delete</a>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h4>Logs</h4>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Type</th>
						<th>Time</th>
					</tr>
				</thead>
				<tbody>
					@forelse($site->logs()->orderBy('created_at', 'DESC')->limit(50)->get() as $log)
						<tr>
							<td><a href="/log/{{$log->id}}">{{$log->event_name}}</a></td>
							<td>{{$log->created_at->format('Y-m-d H:i:s') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="2"><em>No logs associated with this site.</em></td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

@endsection

@section('javascript')
	@parent
	<script>
		$(document).ready(function() {
			$('#revealKeysLink').on('click', function() {
				$('#keys').removeClass('hidden');
			});
			$('#requestForm').on('submit', function(event) {
				event.preventDefault();
				var type = $('#type').val();
				var privateKey = $('#private').val();
				var payload = $('#payload').val();

				$.post('/api/v0/push', {type: type, private: privateKey, payload: payload}, function(data) {
					$('#response').html(JSON.stringify(data));
				});
			});
		});
	</script>
@endsection