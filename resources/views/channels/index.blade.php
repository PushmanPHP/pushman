@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2>Channels <small>for {{$site->name}}</small></h2>

			<table class="table table-condensed table-bordered">
				<thead>
					<tr>
						<th>Name</th>
						<th>Token</th>
						<th>Refreshes</th>
						<th>Max Connections</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>
					@forelse($site->channels as $channel)
						<tr>
							<td><a href="/sites/{{$site->id}}/channels/{{$channel->id}}">{{$channel->name}}</a></td>
							<td><code>{{$channel->public}}</code></td>
							<td>{!! $channel->refreshes() !!}</td>
							<td>{{ $channel->max_connections }}</td>
							<td>{{ $channel->created_at->format('jS F Y H:i') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="5"><em>No channels?! Not even public? Better build one for this to work.</em></td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-lg-6 text-left"><a class="btn btn-sm btn-default" href="/sites/{{$site->id}}">Back to Site</a></div>
				<div class="col-lg-6 text-right"><a class="btn btn-sm btn-success" href="/sites/{{$site->id}}/channels/create">Create Channel</a></div>
			</div>
		</div>
	</div>

@endsection