@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-4 col-lg-offset-4">
			<div class="panel panel-default">
				<div class="panel-body">
					{!! Form::open(['url' => '/sites/' . $site->id . '/channels', 'class' => 'form-horizontal']) !!}

						<legend>Build a Channel</legend>
					
						<!-- Name Field -->
						<div class="form-group">
							{!! Form::label('name', 'Name', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('name', null, ['class' => 'form-control']) !!}
								{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- refreshes Field -->
						<div class="form-group">
							{!! Form::label('refreshes', 'Auto', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::select('refreshes', ['yes' => 'Yes', 'no' => 'No'], 'yes', ['class' => 'form-control']) !!}
								{!! $errors->first('refreshes', '<p class="help-block">:message</p>') !!}
								<p class="help-block"><small>Should the token auto refresh every night?</small></p>
							</div>
						</div>

						<!-- max_connections Field -->
						<div class="form-group">
							{!! Form::label('max_connections', 'Max', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::input('number', 'max_connections', env('PUSHMAN_MAX', 3), ['class' => 'form-control']) !!}
								{!! $errors->first('max_connections', '<p class="help-block">:message</p>') !!}
								<p class="help-block"><small>Max Concurrent Connections</small></p>
							</div>
						</div>
						

						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="submit" class="btn btn-success btn-block">Build Channel</button>
							</div>
						</div>

					{!! Form::close() !!}
				</div>
				<div class="panel-footer">
					<a href="/sites/{{$site->id}}/channels">Return to Channel Management</a>
				</div>
			</div>
		</div>
	</div>

@endsection