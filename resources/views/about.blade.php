@extends('app')

@section('container')

	<div class="background bottom40">
		<div class="container">

			<h1 class="bottom40">About Pushman</h1>

			<div class="bottom20">
				<h4>Pushman uses Web Sockets so you don't have to.</h4>
			</div>

		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-12 about_font">
				<h5>Whats wrong with AJAX?</h5>
				<p>Currently with AJAX technology, viewers on your site initiate a connection after the page has loaded to get additional resources. Maybe you use this to make prettier loading screens, or maybe to navigate the site without refreshing the entire site.</p>
				<p>But there is a problem, the client must initiate the connection. And must ping your server once every few seconds to grab new resources or check the progress on something.</p>

				<h5>How does Pushman change that?</h5>

				<p>Pushman maintains a constant connection via Web Socket to your client and can stream events down that pipe at any time, regardless of the user pressing a button or initiating a connection.</p>
				<p>You can then push events to us via a normal HTTP POST request and we'll stream it down to your user.</p>

				<h5>Why would I use it?</h5>

				<p>Say for example you run a blogging site, and Fred is editing a blog post. But then Jim also logs in to edit that page. Whoever finishes last is going to overwrite the work of the other! Pushman can push the event down to each client to ensure they are aware and can sort it out themselves.</p>

				<p>Maybe you're building a chat program and want to stream messages down to the users so they don't have to refresh the page. AJAX can be quite wasteful pinging your server every few seconds checking for messages.</p>

			</div>
		</div>
	</div>

@endsection