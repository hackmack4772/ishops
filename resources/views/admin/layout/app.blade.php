<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel Shop :: Administrative Panel</title>
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ asset('admin_assets/plugins/fontawesome-free/css/all.min.css')}}">
		<link rel="stylesheet" href="{{ asset('admin_assets/plugins/dropzone/css/dropzone.min.css')}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ asset('admin_assets/css/adminlte.min.css')}}">
		<link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css')}}">
	</head>
	<body class="hold-transition sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<!-- Navbar -->
		@include('admin.layout.header')
        @include('admin.layout.sidebar')

@yield('content')
			<!-- /.content-wrapper -->
			<footer class="main-footer">

				<strong>Copyright &copy; 2014-2022 AmazingShop All rights reserved.
			</footer>

		</div>
		<!-- ./wrapper -->
		<!-- jQuery -->
		<script src="{{ asset('admin_assets/plugins/jquery/jquery.min.js')}}"></script>
		<!-- Bootstrap 4 -->
		<script src="{{ asset('admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{ asset('admin_assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
		<!-- AdminLTE App -->
		<script src="{{ asset('admin_assets/js/adminlte.min.js')}}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{{ asset('admin_assets/js/demo.js')}}"></script>
        <script>
        $.ajaxSetup({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         </script>
        @yield("script")
	</body>
</html>
