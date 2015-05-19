<nav class="navbar navbar-default {{ $className or '' }}" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<li><a href="/">Home</a></li>
				<li><a href="/about">About</a></li>
				<li><a href="/documentation">Documentation</a></li>
				@if(Auth::guest())
					<li><a href="/demo">Demo</a></li>
				@endif
				@if(Auth::check() AND user()->isAdmin())
					<li><a href="/users">Users</a></li>
				@endif
			</ul>
			<ul class="nav navbar-nav navbar-right">
				@if(!Auth::check())
					<li><a href="/auth/login">Login</a></li>
				@else
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/settings">Settings</a></li>
							<li class="divider"></li>
							<li><a href="/auth/logout">Logout</a></li>
						</ul>
					</li>
				@endif
			</ul>
		</div>
	</div>
</nav>