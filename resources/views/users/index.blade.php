@extends('app')

@section('content')

	<h3>Users</h3>

	<div class="panel panel-default">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Username</th>
					<th>Email</th>
					<th>Status</th>
					<th>Sites</th>
				</tr>
			</thead>
			<tbody>
				@forelse($users as $user)
					<tr>
						<td><a href="/users/{{$user->id}}">{{$user->username}}</a></td>
						<td>{{$user->email}}</td>
						<td>{{ucwords($user->status)}}</td>
						<td>{{$user->sites->count()}}</td>
					</tr>
				@empty
					<tr>
						<td colspan="4"><em>No users found.</em></td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

@endsection