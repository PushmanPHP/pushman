@extends('app')

@section('content')
	
	<div class="row" id="ban_container">
		<div class="col-lg-12">
			<h2>Site Bans</h2>

			<div class="panel panel-default">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>IP</th>
							<th>Banned at</th>
							<th>Duration</th>
							<th>Unbanned at</th>
							<th>Unbanned in</th>
							<th>Active</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($bans as $ban)
							<tr>
								<td>{{ $ban->ip }}</td>
								<td>{{ $ban->created_at }}</td>
								<td>{{ $ban->duration }} days</td>
								<td>{{ $ban->created_at->addDays($ban->duration) }}</td>
								<td>{{ $ban->created_at->addDays($ban->duration)->diffForHumans() }}</td>
								<td>{{ ucwords($ban->active) }}</td>
								<th>
									<a href="#" v-on="click:editBan('{{$ban->id}}', '{{$ban->ip}}', '{{$ban->duration}}', '{{$ban->active}}')">
										<span class="glyphicon glyphicon-pencil"></span>
									</a>
									<a href="/sites/{{$site->id}}/unban/{{$ban->id}}"><span class="glyphicon glyphicon-remove"></span></a>
								</th>
							</tr>
						@empty
							<tr>
								<td colspan="5"><em>No one has been banned :)</em></td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<div class="panel panel-default" v-show="visibleEditor">
				<div class="panel-heading">
					<h3 class="panel-title">Edit a Ban</h3>
				</div>
				<div class="panel-body">
					{!! Form::open(['class' => 'form-horizontal', 'v-on' => 'submit: submitEditForm']) !!}

						<!-- ip Field -->
						<div class="form-group">
							{!! Form::label('ip', 'IP', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('ip', null, ['class' => 'form-control', 'v-model' => 'ip']) !!}
								{!! $errors->first('ip', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Duration Field -->
						<div class="form-group">
							{!! Form::label('duration', 'Duration', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('duration', null, ['class' => 'form-control', 'v-model' => 'duration']) !!}
								{!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- active Field -->
						<div class="form-group">
							{!! Form::label('active', 'Active', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::select('active', ['yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control', 'v-model' => 'active']) !!}
								{!! $errors->first('active', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="submit" class="btn btn-success btn-block">Edit</button>
							</div>
						</div>
					
					{!! Form::close() !!}
				</div>
			</div>

		</div>
	</div>

	<div class="row">
        <div class="col-lg-12">
            <a class="btn btn-default btn-sm" href="/sites/{{$site->id}}/subscribers">Back to Subscribers</a>
        </div>
    </div>

@endsection