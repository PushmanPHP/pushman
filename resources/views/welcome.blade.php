@extends('app')

@section('container')

	<div class="cover">
		<div class="container container-cover">
			@include('vendor.flash.message', ['cover' => true])

			<div class="row bottom40">
				<div class="col-lg-12">
					<img src="/img/logo.png" alt="Pushman Logo">
					<h1>Pushman</h1>
					<p>The open source web socket event handler.</p>
				</div>
			</div>

			<div class="row bottom40">
				<div class="col-lg-4">
					<img src="/img/simple.png" alt="Simple">
					<div class="text">
						<h4>Simple</h4>
						<p>Build new events in seconds</p>
					</div>
				</div>
				<div class="col-lg-4">
					<img src="/img/secure.png" alt="Secure">
					<div class="text">
						<h4>Secure</h4>
						<p>Open source at <a href="http://github.com/duffleman/pushman">GitHub</a></p>
					</div>
				</div>
				<div class="col-lg-4">
					<img src="/img/ready.png" alt="Ready">
					<div class="text">
						<h4>Ready</h4>
						<p>Feature rich</p>
					</div>
				</div>
			</div>

			<div class="row bottom40">
				<div class="col-lg-12">
					<small>George Miller &amp; Shad Jahangir &copy; 2015<br>MIT License</small>
				</div>
			</div>
		</div>
	</div>

@endsection