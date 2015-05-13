@extends('app')

@section('content')

	<h1>Documentation</h1>

	<div class="alert alert-info">
		<h4>In-progress</h4>
		<p>Since this beta software, still adding some features so documentation is going to be sketchy.</p>
	</div>

	<h2>Installation</h2>
	<p>Pushman works fantastically on Laravel's <a href="http://forge.laravel.com">Forge</a>! <strong>You still need to install prerequisites though.</strong></p>

	<h3>PHP Extensions</h3>
	<p>Pushman requires <a href="http://zeromq.org/">ZeroMQ</a> which is a custom binary (Windows and Linux), along with it's PHP extension.</p>
	<p>According to zmq's installation set, build it for a Forge server is very easy!</p>
	
	<h4>Step 1 - Install the Binary</h4>
	<div class="alert alert-info">When installing on Windows, you can just install the .exe from <a class="alert-link" href="http://zeromq.org/distro:microsoft-windows">their website</a>.</div>
	<a href="http://zeromq.org/area:download">Excerpt from the Official Website</a>
	<div class="well">
		<h6>To build on UNIX-like systems</h6>
		<p>If you have free choice, the most comfortable OS for developing with ZeroMQ is probably Ubuntu.</p>
		<ol>
			<li>Make sure that <code>libtool</code>, <code>pkg-config</code>, <code>build-essential</code>, <code>autoconf</code>, and <code>automake</code> are installed.</li>
			<li>Check whether <code>uuid-dev</code> package, <code>uuid</code>/<code>e2fsprogs</code> RPM or equivalent on your system is installed.</li>
			<li>Unpack the .tar.gz source archive.</li>
			<li>Run <code>./configure</code>, followed by <code>make</code>.</li>
			<li>To install ZeroMQ system-wide run <code>sudo make install</code>.</li>
			<li>On Linux, run <code>sudo ldconfig</code> after installing ZeroMQ.</li>
		</ol>

		<strong>OR.... apt-get you say?</strong><br>
		<code>apt-get install libzmq3-dev</code>
	</div>

	<h4>Step 2 - Install the php extension</h4>
	<a href="http://zeromq.org/bindings:php#toc3">Excerpt from their website.</a>
	<div class="well">
		<code>sudo pecl install zmq-beta</code><br>
		<em>I found this didn't work very well so I built the extension myself on Forge...</em><br><br>
		<code>git clone git://github.com/mkoppanen/php-zmq.git</code><br>
		<code>$ cd php-zmq</code><br>
		<code>phpize && ./configure</code><br>
		<code>$ sudo make</code><br>
		<code>$ sudo make install</code>
	</div>

	<p>Finally, after building the extension: create a new zmq.ini file in your /etc/php5/mods-available directory (if running Forge) or just append the line <code>extension=zmq.so</code> to your php.ini file.</p>

	<p>If running Forge, ensure you run <code>php5enmod zqm</code> to enable the extension and restart FPM with <code>sudo /etc/init.d/php5-fpm restart</code>. I fiddled around a little but it wasn't too tricky to install that requirement.</p>
	

	<h3>Port Requirements</h3>
	<p>Pushman requires port <code>5555</code> but you do <em>not</em> need to write a firewall rule for it, but ensure that nothing else is stealing that port.</p>
	<p>Pushman runs publically on port <code>8080</code>, you can change it in the source, hopefully in future upgrades that port will be configurable. You <em>need</em> to setup a firewall rule to allow access to port <code>8080</code>.</p>

	<h3>Installing the Code</h3>
	<p>On forge, you can just build a new site, and give the it the Github repo to install itself. <code>Duffleman/pushman</code> on the <em>master</em> branch is what you need to enter.</p>
	<p>On a regular server, git clone the directory and run <code>composer install --no-dev</code> to install the requirements.</p>

	<div class="alert alert-warning">If this fails, you may have failed to install the zmq extension. (Instructions above).</div>

	<h4>Configuration</h4>
	<p>Pushman requires a database, so for both Forge and a regular server, enter your Database editor of choice, or sqlite, and build a database and enter the details in the <code>.env</code> file in the root web directory.</p>

	<p>Once the database configuration is set, you can run <code>php artisan migrate</code> followed by <code>php artisan key:generate</code> to publish the database layout. Or on Forge, just redeploy the site.</p>

	<p><strong>You MUST set an App Key.</strong></p>

	<h4>Runtime</h4>
	<p>Pushman itself can then be run by using <code>php artisan pushman:run</code>. I highly recommend setting up a supervisord task for this or in Forge, go into your server tab and enter the full path to artisan and Forge will auto monitor the task for you.</p>

	<h2>Usage</h2>

	<h3>Registration</h3>
	<p>Pushman allows for multiple users and multiple sites, but as the server owner you should set yourself up as the admin. You can do this 2 different ways.</p>
	<p>When registering, there is an <code>Override</code> text field at the bottom of the reigstration form. If you enter your App key from the .env file, it will set you as an administrator. Or you can enter your database and change your account status from <code>waiting</code> to <code>admin</code>.</p>

	<h3>Handling Users</h3>
	<p>Other users can then register and you may "promote" them, (set them as Active), or "ban" them, (stop them logging in or using any of their sites). No user can register and instantly use the site, you must approve every user that registers on your instance of Pushman.</p>

	<h3>Building Sites</h3>
	<p>Pushman allows for multiple sites, so you only need one Pushman server and it can handle all of your sites.</p>
	<p>You do this by creating multiple sites and for each site you have a public and private key.</p>
	<p>A <strong>public</strong> key is used by the client to receive messages so you can give it out freely, a <strong>private</strong> key is used to send HTTP GET/POST requests to the server to push events to your clients. Never give this out or allow it to be seen on your repositories.</p>

	<p>From the site panel you can push test events to clients to test any clients you build.</p>

	<h3>Javascript Client</h3>
	<p>Building a Javascript client is super easy, you don't even need a special Pushman library! We highly recommend using <a href="http://autobahn.ws/">Autobahn.ws</a> which allows you to easily open Web Sockets.</p>

	<p>From there you only need to open a webSocket to <code>http://yoursite.com:8080?token=PUBLIC_TOKEN</code>.</p>

	<h4>Example Client</h4>
<pre>
var conn = new ab.Session('ws://pushman.dfl.mn:8080?token=hnalVjXQjOPisZTXLqUy',
		function() {
			conn.subscribe('kittens', function(topic, data) {
			// Add Logic to your application here.
			console.log('kittens event received !');
			console.log(topic);
			console.log(data);
		});
	},
	function() {
		console.warn('WebSocket connection closed');
	},
	{'skipSubprotocolCheck': true}
);
</pre>

	<h3>Logging</h3>
	<p>Every event you push per site to a client is logged so you can revisit it and see how your application is doing.</p>

	<h2>Pushing Events via HTTP</h2>
	<p>Her is the important part, after everything is setup, after Pushman is running, after clients are listening to events... you need to send an event!</p>
	
	<p>The idea is simple, you send a <code>POST</code> request to <code>http://yoursite.com/api/v0/push</code>.</p>

	<p>Within that POST request, you need 2 required parameters and 1 optional parameter.</p>

	<h4>Push Event</h4>
	<span class="label label-danger">AUTH</span> <span class="label label-info">POST</span> :: /api/v0/push
	<p>This endpoint pushes an event to all listening clients on a single site.</p>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Parameter</th>
				<th>Required</th>
				<th>Requirements</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>private</td>
				<td><span class="label label-success">yes</span></td>
				<td>It must be a valid private key from one of your sites.</td>
				<td>The private key of a site you manage.</td>
			</tr>
			<tr>
				<td>type</td>
				<td><span class="label label-success">yes</span></td>
				<td>Must be a string with no spaces.</td>
				<td>The event type name, EG. "blog_post".</td>
			</tr>
			<tr>
				<td>payload</td>
				<td><span class="label label-danger">no</span></td>
				<td>Must be a JSON string.</td>
				<td>The set of data you may want the client to use.</td>
			</tr>
		</tbody>
	</table>

	<div class="row">
		<div class="col-lg-6">
			<h5>Request</h5>
			<pre>
POST /api/v0/push HTTP/1.1
Host: localhost
Cache-Control: no-cache
Content-Type: multipart/form-data;
{
	"private": "qYna0HGtVAUyCePv67RwCLh6",
	"type": "kittens",
	"payload": {
		"foo" : "bar"
	}
}
			</pre>
		</div>
		<div class="col-lg-6">
			<h5>Response</h5>
			<pre>
{
    "status": "success",
    "message": "Event has been pushed"
}
			</pre>
		</div>
	</div>

@endsection