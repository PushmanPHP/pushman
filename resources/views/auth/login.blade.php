@extends('app')

@section('container')
	
	<div class="cover">
		
		<div class="container">

			@include('vendor.flash.message', ['cover' => true, 'top' => '40px'])
			
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<h2>Login</h2>

					<div class="panel panel-primary">
						<div class="panel-body text-primary">
							{!! Form::open(['class' => 'form-horizontal']) !!}

							<!-- username Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username']) !!}
									{!! $errors->first('username', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- password Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
									{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- Submit Button -->
							<div class="form-group">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary btn-block">Login</button>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6 text-left">
									<a href="/auth/register" class="btn btn-info">Register</a>
								</div>
								<div class="col-lg-6 text-right">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="remember" value="yes" checked="checked">
											Remember Me
										</label>
									</div>
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