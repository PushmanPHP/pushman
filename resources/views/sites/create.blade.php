@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h4>Create Site</h4>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="panel panel-default">
				<div class="panel-body">
					{!! Form::open(['class' => 'form-horizontal', 'route' => 'sites.store']) !!}
						
						<!-- name Field -->
						<div class="form-group">
							{!! Form::label('name', 'Name', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('name', null, ['class' => 'form-control']) !!}
								{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- url Field -->
						<div class="form-group">
							{!! Form::label('url', 'URL', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('url', 'http://', ['class' => 'form-control']) !!}
								{!! $errors->first('url', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="submit" class="btn btn-primary btn-block">Create</button>
							</div>
						</div>

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection