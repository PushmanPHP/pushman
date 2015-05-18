@extends('app')

@section('content')
	
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ user()->username }}</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="pull-left">Sites</h3>
					<a href="/sites/create" class="btn btn-primary pull-right">Create a Site</a>
				</div>
			</div>
			<div class="row">
				@forelse(user()->sites as $site)
					<div class="col-lg-3">
						<div class="thumbnail">
							<div class="caption text-center">
								<h4>{{str_limit($site->name, 10)}}</h4>
								<p>{{$site->url}}</p>
								<p>
									<a class="btn btn-info" href="/sites/{{$site->id}}">Open</a>
								</p>
							</div>
						</div>
					</div>
				@empty
					<div class="col-lg-12">
						<div class="alert alert-info">You have no sites. <a class="alert-link" href="/sites/create">Create a Site</a>.</div>
					</div>
				@endforelse
			</div>
		</div>
	</div>

@endsection