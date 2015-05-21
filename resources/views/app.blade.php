<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Pushman</title>

		<!-- Bootstrap CSS -->
		<link href="/css/all.css" rel="stylesheet">

		<!-- Open Sans -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		@include('_navigation')
		
		@section('container')
			<div class="container">
				@include('vendor.flash.message')
				@yield('content')
			</div>
		@show

		@section('javascript')
			<script src="/js/all.js"></script>
		@show
	</body>
</html>
