@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12 text-center">
			<h2>Login</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="panel panel-default">
				<div class="panel-body">
					{!! Form::open(['role' => 'form', 'class' => 'form-horizontal']) !!}

						<!-- username Field -->
						<div class="form-group">
							{!! Form::label('username', 'Username', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::text('username', null, ['class' => 'form-control']) !!}
								{!! $errors->first('username', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- password Field -->
						<div class="form-group">
							{!! Form::label('password', 'Password', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::password('password', ['class' => 'form-control']) !!}
								{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
							</div>
						</div>



						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="submit" class="btn btn-primary btn-block">Login</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-5 col-sm-offset-2">
								<a href="/auth/register">Register</a>
							</div>
							<div class="col-sm-5">
								<label class="checkbox" for="rememberBox">
									<input type="checkbox" name="remember" data-toggle="checkbox" checked="checked" value="yes" id="rememberBox">
									Remember Me
								</label>
							</div>
						</div>
					
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection

@section('javascript')
	@parent
	<script>
		$(document).ready(function() {
			$('[data-toggle="checkbox"]').radiocheck();
		});
	</script>
@endsection