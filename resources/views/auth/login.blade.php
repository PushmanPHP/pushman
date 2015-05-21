@extends('app')

@section('container')
	
	<div class="background bottom40">
		<div class="container">
			<h1 class="bottom40">{{ \Lang::get('common.login') }}</h1>
		</div>
	</div>

	<div class="container">

		@include('vendor.flash.message')
		
		<div class="row">
			<div class="col-lg-6 col-lg-offset-3">

				<div class="panel panel-primary">
					<div class="panel-body text-primary">
						{!! Form::open(['class' => 'form-horizontal']) !!}

						<!-- username Field -->
						<div class="form-group">
							<div class="col-sm-12">
								{!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => \Lang::get('common.username')]) !!}
								{!! $errors->first('username', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- password Field -->
						<div class="form-group">
							<div class="col-sm-12">
								{!! Form::password('password', ['class' => 'form-control', 'placeholder' => \Lang::get('common.password')]) !!}
								{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
							</div>
						</div>

						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-12">
								<button type="submit" class="btn btn-primary btn-block">{{ \Lang::get('common.login') }}</button>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6 text-left">
								<a href="/auth/register" class="btn btn-info">{{ \Lang::get('common.register') }}</a>
							</div>
							<div class="col-lg-6 text-right">
								<div class="checkbox">
									<label>
										<input type="checkbox" value="remember" value="yes" checked="checked">
										{{ \Lang::get('common.rememberme') }}
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

@endsection