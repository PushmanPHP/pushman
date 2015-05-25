@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h1>Settings</h1>
				
			{!! Form::open(['class' => 'form-horizontal']) !!}

				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Locale</h3>
					</div>
					<div class="panel-body">
						<!-- locale Field -->
						<div class="form-group">
							{!! Form::label('locale', 'Locale', ['class' => 'control-label col-sm-2']) !!}
							<div class="col-sm-10">
								{!! Form::select('locale', $locales, user()->locale, ['class' => 'form-control']) !!}
								{!! $errors->first('locale', '<p class="help-block">:message</p>') !!}
							</div>
						</div>
						<!-- Submit Button -->
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="submit" class="btn btn-primary btn-block">Update</button>
							</div>
						</div>
					</div>
				</div>


			{!! Form::close() !!}

		</div>
	</div>

@endsection