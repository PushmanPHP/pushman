@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h2>Livestream Logs</h2>
			<div class="panel panel-success">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Log</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody id="tableBody">
						<tr></tr>
						<tr id="removeMe">
							<td colspan="2"><em>No logs yet...</em></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h4>Older Logs</h4>
			<panel class="panel-default">
				<table class="table table-bordered table-condensed table-striped">
					<thead>
						<tr>
							<td>Log</td>
							<td>Date</td>
						</tr>
					</thead>
					<tbody>
						@forelse($logs as $log)
							<tr>
								<td>{{$log->log}}</td>
								<td>{{$log->created_at->format('Y-m-d H:i:s') }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="2"><em>No logs.</em></td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</panel>
		</div>
	</div>

@endsection

@section('javascript')
	@parent
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script>
	var conn = new ab.Session('ws://127.0.0.1:{{$port}}?token={{$site->public}}',
        function() {
            conn.subscribe('internal', function(topic, data) {
                $('#removeMe').remove();

                $('#tableBody > tr:first').before('<tr><td>' + data.log + '</td><td>'+ data.timestamp +'</td></tr>');
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
	</script>
@endsection