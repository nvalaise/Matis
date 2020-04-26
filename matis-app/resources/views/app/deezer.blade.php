@extends('layouts.app')

@section('title', 'Deezer')

@section('asset')
<script src="{{ mix('js/vue-calendar-heatmap.browser.js') }}"></script>
@endsection

@section('sidebar-deezer')	
	<li class="nav-item has-treeview menu-open">
		<a href="#" class="nav-link active">
			<i class="nav-icon fas fa-code"></i>
			<p>
				Deezer
				<i class="right fas fa-angle-left"></i>
				@if(Auth::check() && Auth::user()->has('deezer'))
            	<span class="right badge badge-danger">Active</span>
            	@else
            	<span class="right badge badge-danger">Inactive</span>
            	@endif
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'home' }"><i class="far fa-circle nav-icon"></i>Home</router-link>
			</li>
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'playlists' }"><i class="far fa-circle nav-icon"></i>Playlists</router-link>
			</li>
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'social' }"><i class="far fa-circle nav-icon"></i>Social</router-link>
			</li>
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'history' }"><i class="far fa-circle nav-icon"></i>History</router-link>
			</li>
		</ul>
	</li>
@stop

@section('content')
	<h3>@yield('title')</h3>

	<router-view></router-view>
@endsection

@section('vue')
    <script src="{{ mix('js/deezer.js') }}"></script>
@endsection