@extends('app')

@section('content')

	<div class="row bottom20">
		<div class="col-lg-12">
			<h2>{{ $channel->name }}</h2>
			<h5>{{$site->url}} - <small><code>{{$channel->public}}</code></small></h5>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2>{{ $channel->current_users() }}</h2>
					<h6>{{ str_plural('Subscriber', $channel->current_users()) }}</h6>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2><a href="#" id="max_connections">{{ $channel->max_connections }}</a></h2>
					<h6>Max Connections</h6>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2>{{ $channel->events_fired() }}</h2>
					<h6>{{ str_plural('Event', $channel->events_fired()) }} Fired</h6>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="thumbnail">
				<div class="caption text-center">
					<h2>{{ ucwords($channel->refreshes) }}</h2>
					<h6>Auto Refreshes</h6>
				</div>
			</div>
		</div>
	</div>

	<div class="row bottom40">
		<div class="col-lg-3">
			<a href="/sites/{{$site->id}}/channels/{{$channel->id}}/regenerate" class="btn btn-warning btn-block swal">Regenerate Token</a>
		</div>
		<div class="col-lg-3">
			<a href="/sites/{{$site->id}}/channels/{{$channel->id}}/toggle" class="btn btn-warning btn-block swal">Toggle Auto-refresh</a>
		</div>
		@if($channel->name !== 'public')
			<div class="col-lg-3">
				<a href="/sites/{{$site->id}}/channels/{{$channel->id}}/delete" class="btn btn-danger btn-block swal">Delete Channel</a>
			</div>
		@endif
	</div>

	<div class="row">
		<div class="col-lg-12">
			<a class="btn btn-default btn-sm" href="/sites/{{$site->id}}/channels">Back to Channel Management</a>
		</div>
	</div>

@endsection

@section('javascript')
	@parent
	<script>
	$(document).ready(function() {
		$.fn.editable.defaults.mode = 'inline';
		$('#max_connections').editable({
			type: 'text',
			pk: '{{$channel->id}}',
			url: '/sites/{{$site->id}}/channels/{{$channel->id}}/max',
			title: 'Max Connections',
			success: function(response, newValue) {
				if(response.type == 'error') {
					swal(response.message);
					return false;
				}
			},
			error: function(response, newValue) {
				swal(response.responseJSON.value[0]);
			}
		});
	});
	</script>
@endsection