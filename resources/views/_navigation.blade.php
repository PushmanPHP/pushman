<nav class="navbar navbar-default" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="/">Pushman</a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<li class="active"><a href="/">Home</a></li>
			@if(Auth::check())
				<li><a href="/sites">Sites</a></li>
				<li><a href="/users">Users</a></li>
			@endif
			<li><a href="/docs">Docs</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			@if(Auth::check())
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="/settings">Settings</a></li>
						<li class="divider"></li>
						<li><a href="/auth/logout">Logout</a></li>
					</ul>
				</li>
			@else
				<li><a href="/auth/login">Login</a></li>
			@endif
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>