@extends('app')

@section('content')

	
	<div class="row">
		<div class="col-lg-12">
			<h2>Subscribers</h2>
			<h3>{{ $site->name }}</h3>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>Resource ID</th>
						<th>IP</th>
						<th>Connected</th>
						<th>Online Time</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($subscribers as $sub)
						<tr>
							<td>{{ $sub->resource_id }}</td>
							<td>{{ $sub->ip }}</td>
							<td>{{ $sub->created_at }}</td>
							<td>{{ $sub->created_at->diffForHumans(null, true) }}</td>
							<td>
								<a class="btn btn-xs btn-warning swal" href="/sites/{{$site->id}}/subscribers/{{$sub->resource_id}}/disconnect">Disconect</a>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="4" style="padding:0;">
								<table class="table table-condensed table-bordered" style="margin-bottom:0;">
									<thead>
										<tr>
											<th>Channel</th>
											<th>Event</th>
										</tr>
									</thead>
									<tbody>
										@foreach($sub->events() as $event)
										<tr>
											<td>{{$event['channel_name']}}</td>
											<td>{{$event['event_name']}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5"><em>No one is listening for events.</em></td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

@endsection