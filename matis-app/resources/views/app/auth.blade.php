@extends('layouts.app')

@section('title', 'Home')

@section('app')
<div class="container mt-2">
	
	@if ( Session::has('flash_message') )

	<div class="row">

		<div class="alert {{ Session::get('flash_type') }}" role="alert">
			<p>{!! Session::get('flash_message') !!}</p>
		</div>
	</div>
	@endif

	<div class="row">
		<div class="col-12">
			<div class="card">
				<h1 class="card-header text-center">Authentication</h1>
				<div class="card-body">
					<h2 class="card-title">Applications available</h2>
					<div class="row">
						<div class="col-3 mr-1">
							<div class="card">
								<img src="{{ asset('img/logo/deezer-logo-circle.png') }}" class="@if(Auth::guard('deezer')->check()) bg-success @else bg-light @endif p-4 card-img-top" alt="deezer logo ransparent">
								<div class="card-body">
									<h5 class="card-title">Deezer</h5>
									<p class="card-text">Listen your music.</p>
									<a href="{{ route('auth.deezer.login') }}" class="btn btn-success">Auth</a>
									@if(Auth::guard('deezer')->check())
										<a href="{{ route('auth.deezer.create') }}" class="btn btn-primary disable">Save</a>
									@else
										<a href="#" class="btn btn-secondary">Save</a>
									@endif
									<a href="{{ route('auth.deezer.logout') }}" class="btn btn-danger">Logout</a>
								</div>
							</div>
						</div>
						<div class="col-3 mr-1">
							<div class="card">
								<img src="{{ asset('img/logo/spotify-logo-transparent-hd-png.png') }}" class="bg-light card-img-top" alt="spotify logo transparent">
								<div class="card-body">
									<h5 class="card-title">Spotify</h5>
									<p class="card-text">Listen your music.</p>
									<a href="#" class="btn btn-success">Login</a>
									<a href="#" class="btn btn-danger">Logout</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"></div>
				<div class="panel-body">
					<div class="row">
						
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>
@stop