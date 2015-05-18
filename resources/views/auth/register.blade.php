@extends('app')


@section('container')
	
	<div class="cover">
		<div class="container">
			@include('vendor.flash.message', ['cover' => true, 'top' => '40px'])
			
			<div class="row">
				<div class="col-lg-12 text-center">
					<h2>Register</h2>
				</div>
			</div>

			<div class="row text-primary">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="panel panel-default">
						<div class="panel-body">
							{!! Form::open(['class' => 'form-horizontal']) !!}

								<!-- username Field -->
								<div class="form-group">
									<div class="col-sm-12">
										{!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => "Username"]) !!}
										{!! $errors->first('username', '<p class="help-block">:message</p>') !!}
									</div>
								</div>

								<!-- email Field -->
								<div class="form-group">
									<div class="col-sm-12">
										{!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
										{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
									</div>
								</div>

								<!-- Password Field -->
								<div class="form-group">
									<div class="col-sm-12">
										{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
										{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
									</div>
								</div>

								<!-- Password Field -->
								<div class="form-group">
									<div class="col-sm-12">
										{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
										{!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
									</div>
								</div>

								<!-- override Field -->
								<div class="form-group">
									<div class="col-sm-12">
										{!! Form::text('override', null, ['class' => 'form-control', 'placeholder' => 'override key - leave this empty']) !!}
										{!! $errors->first('override', '<p class="help-block">:message</p>') !!}
									</div>
								</div>

								<!-- Submit Button -->
								<div class="form-group">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-primary btn-block">Register</button>
									</div>
								</div>
							
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>




@endsection