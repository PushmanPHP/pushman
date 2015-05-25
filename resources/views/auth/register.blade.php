@extends('app')


@section('container')
	
	<div class="background bottom40">
		<div class="container">
			<h1 class="bottom40">Register</h1>
		</div>
	</div>

	<div class="container">
		@include('vendor.flash.message')
		<div class="row text-primary">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="panel panel-default">
					<div class="panel-body">
						{!! Form::open(['class' => 'form-horizontal']) !!}

							<!-- username Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => \Lang::get('common.username')]) !!}
									{!! $errors->first('username', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- email Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => \Lang::get('common.email')]) !!}
									{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- Password Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::password('password', ['class' => 'form-control', 'placeholder' => \Lang::get('common.password')]) !!}
									{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- Password Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => \Lang::get('common.passwordconfirmation')]) !!}
									{!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- override Field -->
							<div class="form-group">
								<div class="col-sm-12">
									{!! Form::text('override', null, ['class' => 'form-control', 'placeholder' => \Lang::get('common.override')]) !!}
									{!! $errors->first('override', '<p class="help-block">:message</p>') !!}
								</div>
							</div>

							<!-- Submit Button -->
							<div class="form-group">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary btn-block">{{ \Lang::get('common.register') }}</button>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<p class="text-left help-block">You should really consider building your own Pushman instance from our source code! <a href="http://github.com/Duffleman/pushman">Check it out here</a>. If you register online with our site we cannot guarentee 24 hour uptime and all channels are limited to {{ env('PUSHMAN_MAX',3) }} max connections.</p>
									<p class="text-left help-block">If you build your own instance, you can have unlimited everything!</p>
								</div>
							</div>
						
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>




@endsection