@extends('app')

@section('container')

	<div class="cover-about bottom40">
		<div class="container">

			<h2 class="bottom40">Documentation</h2>

		</div>
	</div>

	<div class="container meaty_font">

		<div class="row">
			<div class="col-lg-6">
				<h6>Table of Contents</h6>
				<div class="well">
					<ul>
						<li><a href="#apiEndpoints">API Endpoints</a></li>
						<li><a href="#siteUsage">Using the Site</a></li>
						<li><a href="#exampleClient">Example Client (Javascript)</a></li>
						<li><a href="#laravel">Laravel Extenders</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row" id="apiEndpoints">
			<div class="col-lg-12">
				<h2>API Endpoints</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<h3>Push Event</h3>
				<span class="label label-danger">AUTH</span> <span class="label label-info">POST</span> :: /api/push
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
							<td>It must be a valid private key from one of your sites, exactly 60 characters.</td>
							<td>The private key of a site you manage.</td>
						</tr>
						<tr>
							<td>event</td>
							<td><span class="label label-success">yes</span></td>
							<td>Must be a string with no spaces.</td>
							<td>The event name, EG. "blog_post".</td>
						</tr>
						<tr>
							<td>channels</td>
							<td><span class="label label-danger">no</span></td>
							<td>Must be a valid JSON array of channel names to broadcast to.</td>
							<td>a set of channel names your site has.</td>
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
<pre><code class="json">POST /api/push HTTP/1.1
Host: localhost
Cache-Control: no-cache
Content-Type: multipart/form-data;
{
    "private": "gIcLWblryEvOqplqVpCwNmXZGPjzAYKyNAUtcuuzfNk...",
    "event": "kittens",
    "channels": ["auth"],
    "payload": {
        "foo" : "bar"
    }
}
</code></pre>
					</div>
					<div class="col-lg-6">
						<h5>Response</h5>
<pre><code class="javascript">{
    "status": "success",
    "message": "Event pushed successfully.",
    "event": "kittens",
    "channels": [
        {
            "id": "8",
            "name": "auth",
            "public": "CpluouZMI9m1dejqvGzK",
            "refreshes": "no",
            "max_connections": "100",
            "active_users": "0",
            "events_fired": 17,
            "created_at": "2015-05-18 16:55:41"
        }
    ],
    "site": "dfl.m",
    "timestamp": {
        "date": "2015-05-20 01:38:43.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "payload": {
        "foo": "bar"
    }
}
</code></pre>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<h3>Channel Listing</h3>
				<span class="label label-danger">AUTH</span> <span class="label label-success">GET</span> :: /api/channels
				<p>This method returns a list of all channels associated with your site. (including internal channels and the public chanel)</p>

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
							<td>It must be a valid private key from one of your sites, exactly 60 characters.</td>
							<td>The private key of a site you manage.</td>
						</tr>
					</tbody>
				</table>

				<div class="row">
					<div class="col-lg-6">
						<h5>Request</h5>
<pre><code class="json">POST /api/channels HTTP/1.1
Host: localhost
Cache-Control: no-cache
{
    "private": "gIcLWblryEvOqplqVpCwNmXZGPjzAYKyNAUtcuuzfNk..."
}
</code></pre>
					</div>
					<div class="col-lg-6">
						<h5>Response</h5>
<pre><code class="javascript">[
    {
        "id": "4",
        "name": "public",
        "public": "xhbohWG3OuMXPERaPYRh",
        "refreshes": "no",
        "max_connections": "0",
        "active_users": "0",
        "events_fired": "65",
        "created_at": "2015-05-17 17:16:39"
    },
    {
        "id": "9",
        "name": "admin",
        "public": "kDo0fkRCRHck4Oalzyt7",
        "refreshes": "yes",
        "max_connections": "10",
        "active_users": "0",
        "events_fired": "0",
        "created_at": "2015-05-18 16:55:49"
    }
]
</code></pre>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<h3>Channel Information</h3>
				<span class="label label-danger">AUTH</span> <span class="label label-success">GET</span> :: /api/channel
				<p>This method returns information on a channel.</p>

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
							<td>It must be a valid private key from one of your sites, exactly 60 characters.</td>
							<td>The private key of a site you manage.</td>
						</tr>
						<tr>
							<td>channel</td>
							<td><span class="label label-success">yes</span></td>
							<td>Must be a valid channel name.</td>
							<td>a name of a channel your site has.</td>
						</tr>
					</tbody>
				</table>

				<div class="row">
					<div class="col-lg-6">
						<h5>Request</h5>
<pre><code class="json">POST /api/channel HTTP/1.1
Host: localhost
Cache-Control: no-cache
{
    "private": "gIcLWblryEvOqplqVpCwNmXZGPjzAYKyNAUtcuuzfNk...",
    "channel": "auth"
}
</code></pre>
					</div>
					<div class="col-lg-6">
						<h5>Response</h5>
<pre><code class="javascript">{
    "id": "8",
    "name": "auth",
    "public": "CpluouZMI9m1dejqvGzK",
    "refreshes": "no",
    "max_connections": "100",
    "active_users": "0",
    "events_fired": "0",
    "created_at": "2015-05-18 16:55:41",
    "token_expires": {
        "date": "2015-05-18 17:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    }
}
</code></pre>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<hr>
			</div>
		</div>

		<div class="row" id="siteUsage">
			<div class="col-lg-12">
				<h3>Site Usage</h3>

				<div>
					<p>Pushman runs on multiple sites so from your dashboard you need to first build a new site.</p>

					<h4>Sites</h4>
					<p>Each site then has multiple channels you can push events through. By default you won't always need to use channels, if you never touch channels Pushman will default to the public channel.</p>

					<h4>Channels</h4>
					<p>Multiple channels allow for private events and payloads to be pushed to users. Say for example you want to alert admins when a blog post was created, if you always used the 'public' channel, then every user could use their public token to subscribe to that event and listen in.</p>

					<p>In this case, you would create an 'auth' channel and push the 'new_blog' event down that channel which would stop guests listening in.</p>

					<p>You can then have another 'admin' channel where only admin events are pushed. Everyone has access to the public channels, only certain users have access to the other private channels.</p>

					<h6>Max Connections</h6>
					<p>This is just another added measure of security, say you have a team of 6 people to administer your site. You should also set the max connections of your 'admin' channel to 6. Therefore even if someone did get the key, they wouldn't also have a spot on the connection list ready for them, and if a legitimate user cannot connect, you'll know someone grabbed a private token they shouldn't have and reset the token.</p>

					<h6>Auto Refreshing Tokens</h6>
					<p>Say for example you ban someone on your site so they can no longer access your 'auth' channel. If they copied the token for the 'auth' channel, then you'd have to reset your token every single time you ban a user.</p>
					<p>Auto Refreshing tokens are reset every 60 minutes, so your server pings Pushman servers in advanced, grabs the appropriate token for this 60 minute period and passes it onto the client.</p>
					<p>If you ban a user, or demote them from being an admin, then they would only keep access to the channel for 60 minutes at most.</p>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<hr>
			</div>
		</div>

		<div class="row" id="exampleClient">
			<div class="col-lg-12">
				<h3>Javascript Setup</h3>

				<h4>Example Client</h4>
				<table class="table">
					<tbody>
						<tr>
							<th>Site</th>
							<td>http://test.com</td>
						</tr>
						<tr>
							<th>'Public' Channel Token</th>
							<td><code>CXsujMXhMbPlirEBKaFP</code></td>
						</tr>
						<tr>
							<th>'Auth' Channel Token</th>
							<td><code>tKGrLcKIaPzccjHIioXc</code></td>
						</tr>
					</tbody>
				</table>
<pre><code><?php echo(e('<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>')); ?></code></pre>
<pre><code class="javascript"><?php echo(e('var conn = new ab.Session(\'ws://pushman.dfl.mn:8080?token=CXsujMXhMbPlirEBKaFP\',
    function() {
        conn.subscribe(\'auth(tKGrLcKIaPzccjHIioXc)|kittens\', function(topic, data) {
            // Subscribes to the `kittens` event on the `auth` channel.
            console.log(data);
        });
        conn.subscribe(\'kittens_are_cute\', function(topic, data) {
            // Subscribes to the `kittens_are_cute` event on the `public` channel.
            console.log(data);
        });
    },
    function() {
        console.warn(\'WebSocket connection closed\');
    },
    {\'skipSubprotocolCheck\': true}
);')); ?>
</code></pre>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<hr>
			</div>
		</div>

		<div class="row bottom40" id="laravel">
			<div class="col-lg-12">
				<h3>Extending Laravel</h3>
				<p>The <a href="http://github.com/Duffleman/pushman_php">Pushman PHP Library</a> has a ServiceProvider to extend Laravel 5.1.</p>
				<p>Visit the documentation on the GitHub repo, but the idea is simple, require the project with composer and state the <code>Pushman\PHPLib\PushmanServiceProvider</code> in your <code>app/config.php</code> file.</p>
				<p>Any event you call that implements <code>ShouldBroadcast</code> will be pushed to Pushman for you!</p>
			</div>
		</div>
	</div>

@endsection

@section('javascript')
@parent
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.5/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
@endsection