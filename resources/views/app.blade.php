<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Pushman</title>

		<!-- Bootstrap CSS -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link href="/css/flat-ui.min.css" rel="stylesheet">
		<link rel="stylesheet" href="/css/site.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		@include('_navigation')
		
		@include('vendor.flash.message')
		
		@section('container')
			<div class="container">
				@yield('content')
			</div>
		@show

		@section('javascript')
			<script src="//code.jquery.com/jquery.js"></script>
			<script src="/js/flat-ui.min.js"></script>
		@show
	</body>
</html>