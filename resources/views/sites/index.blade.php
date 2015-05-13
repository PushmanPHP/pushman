@extends('app')

@section('content')
	
	<div class="row">
		<div class="col-lg-12">
			<p class="pull-right"><a href="/sites/create">New</a></p>
			<h2>Sites</h2>
		</div>
	</div>

	<div class="row">
		@forelse($user->sites as $site)
		
			<div class="col-lg-3">
				<div class="thumbnail text-center">
					<div class="caption">
						<h3><a href="/sites/{{$site->id}}">{{$site->name}}</a></h3>
						<p>{{$site->url}}</p>
					</div>
				</div>
			</div>

		@empty

			<div class="col-lg-6 col-lg-offset-3">
				<p><em>You don't have any sites set up.</em></p>
			</div>

		@endforelse
	</div>

	@if(Auth::user()->isAdmin() AND env('PUSHMAN_LOG') === 'yes')
	<div class="row">
		<div class="col-lg-2 col-lg-offset-10 text-right">
			<a class="btn btn-info btn-block" href="/log/all">See Pushman Logs</a>
		</div>
	</div>
	@endif

@endsection