@extends('app')

@section('content')
	
	<div class="row">
		<div class="col-lg-12">

			<a class="btn btn-default btn-sm" href="/users">Back to Users</a>

			<div class="row">
				<div class="col-lg-12">
					<h1>{{$user->username}}</h1>

					<p>Status: <code>{{ucwords($user->status)}}</code></p>
				</div>
			
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<a class="btn btn-block btn-success" href="/users/{{$user->id}}/promote">Promote</a>
		</div>
		<div class="col-lg-6">
			<a class="btn btn-block btn-danger" href="/users/{{$user->id}}/ban">Ban</a>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h4>Sites</h4>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Name</th>
						<th>URL</th>
					</tr>
				</thead>
				<tbody>
					@forelse($user->sites as $site)
						<tr>
							<td><a href="/sites/{{$site->id}}">{{$site->name}}</a></td>
							<td>{{$site->url}}</td>
						</tr>
					@empty
						<tr>
							<td colspan="2"><em>No sites.</em></td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	
@endsection