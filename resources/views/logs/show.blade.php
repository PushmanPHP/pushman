@extends('app')

@section('content')

	<div class="row">
		<div class="col-lg-12">
			<a href="/sites/{{$log->site->id}}">Back</a>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h2>{{$log->event_name}}</h2>
			<h6>{{$log->site->public . '.' . $log->event_name}}</h6>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="well">
				{{$log->payload}}
			</div>
		</div>
	</div>

@endsection