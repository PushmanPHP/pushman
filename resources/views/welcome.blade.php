@extends('app')

@section('container')

	<div class="background">
		<div class="container bottom40 tophat">
			<div class="row bottom40">
				<div class="col-lg-12">
					<img src="/img/logo.png" class="logo" alt="Pushman Logo">
					<h1>Pushman</h1>
				</div>
			</div>

			<div class="row bottom40">
				<div class="col-lg-4">
					<img class="home-icon" src="/img/simple.png" alt="Simple">
					<div class="text">
						<h4>Simple</h4>
						<p>Build new events in seconds</p>
					</div>
				</div>
				<div class="col-lg-4">
					<img class="home-icon" src="/img/secure.png" alt="Secure">
					<div class="text">
						<h4>Secure</h4>
						<p>Open source at <a href="http://github.com/duffleman/pushman">GitHub</a></p>
					</div>
				</div>
				<div class="col-lg-4">
					<img class="home-icon" src="/img/ready.png" alt="Ready">
					<div class="text">
						<h4>Ready</h4>
						<p>Feature rich</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row bottom40">
			<div class="col-lg-6">
				<p>
					<small>George Miller &amp; Shad Jahangir &copy; 2015<br>Open-Source Software<br>MIT License</small>
				</p>
				<p>
					<a href="https://twitter.com/Duffleman" class="twitter-follow-button" data-show-count="false">Follow @Duffleman</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				<a href="https://twitter.com/_shadj" class="twitter-follow-button" data-show-count="false">Follow @_shadj</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</p>
			</div>
			<div class="col-lg-6 text-right">
				<p><small>
					Special thanks to
					<ul class="nav">
						<li><a href="https://twitter.com/kfwls">Kaelan Fouwels</a></li>
						<li><a href="https://twitter.com/0xdeafcafe">Alex Reed</a></li>
						<li><a href="http://laravel.com/">Laravel</a></li>
					</ul>
				</small></p>
			</div>
		</div>
	</div>
		

@endsection