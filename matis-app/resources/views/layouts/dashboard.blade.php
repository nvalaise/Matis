@extends('layouts.app')

@section('sub-navbar')
	<div id="subnavbar" class="nav-content">
	    <ul class="tabs tabs-transparent">
			@yield('sub-navbar-content')
	    </ul>
	</div>
@stop

@section('content')
<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
        			<h3>@yield('title')</h3>
				</div>
				<div class="card-body">
					@yield('sub-content')
				</div>
			</div>
		</div>
	</div>
@endsection