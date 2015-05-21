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
				<li><a href="/">{{ Lang::get('navigation.home') }}</a></li>
				@if(Auth::check() AND user()->isAdmin())
					<li><a href="/users">{{ Lang::get('navigation.users') }}</a></li>
				@endif
			</ul>
			<ul class="nav navbar-nav navbar-right">
				@if(!Auth::check())
					<li><a href="/auth/login">{{ Lang::get('navigation.login') }}</a></li>
				@else
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get('navigation.account') }} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/settings">{{ Lang::get('navigation.settings') }}</a></li>
							<li class="divider"></li>
							<li><a href="/auth/logout">{{ Lang::get('navigation.logout') }}</a></li>
						</ul>
					</li>
				@endif
			</ul>
		</div>
	</div>
</nav>