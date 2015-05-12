@extends('app')


@section('content')

	<div class="row">
		<div class="col-lg-12 text-center">
			<h2>Register</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="panel panel-default">
				<div class="panel-body">
					{!! Form::open(['class' => 'form-horizontal']) !!}

						<!-- username Field -->
						<div class="form-group">
							{!! Form::label('username', 'Username', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('username', null, ['class' => 'form-control']) !!}
								{!! $errors->first('username', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- email Field -->
						<div class="form-group">
							{!! Form::label('email', 'Email', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('email', null, ['class' => 'form-control']) !!}
								{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Password Field -->
						<div class="form-group">
							{!! Form::label('password', 'Password', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::password('password', ['class' => 'form-control']) !!}
								{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Password Field -->
						<div class="form-group">
							{!! Form::label('password_confirmation', 'Password', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
								{!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- override Field -->
						<div class="form-group">
							{!! Form::label('override', 'Override', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('override', null, ['class' => 'form-control', 'placeholder' => 'leave this empty']) !!}
								{!! $errors->first('override', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="submit" class="btn btn-primary btn-block">Register</button>
							</div>
						</div>
					
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>



@endsection