@extends('layouts.app')

@section('title', 'Spotify')

@section('asset')
<script src="{{ mix('js/vue-calendar-heatmap.browser.js') }}"></script>
<style type="text/css">
.disabled {
  pointer-events: none;
  cursor: default;
  opacity: 0.6;
  color: #6c757d;
}
</style>
@endsection

@section('sidebar-spotify')	
	<li class="nav-item has-treeview menu-open">
		<a href="#" class="nav-link active">
			<i class="nav-icon fas fa-code"></i>
			<p>
				Spotify
				<i class="right fas fa-angle-left"></i>
				@if(Auth::check() && Auth::user()->has('spotify'))
            	<span class="right badge bg-green">Active</span>
            	@else
            	<span class="right badge badge-danger">Inactive</span>
            	@endif
			</p>
		</a>
		<ul class="nav nav-treeview">
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'home' }" active-class="active" exact><i class="far fa-circle nav-icon"></i>Home</router-link>
			</li>
			<!--li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'artists' }" active-class="active" exact><i class="far fa-circle nav-icon"></i>Artists</router-link>
			</li-->
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'playlists' }" active-class="active" exact><i class="far fa-circle nav-icon"></i>Playlists</router-link>
			</li>
			<!--
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'history' }" active-class="active" exact><i class="far fa-circle nav-icon"></i>History</router-link>
			</li>
			<li class="nav-item">
				<router-link class="nav-link" :to="{ name: 'social' }" active-class="active" exact><i class="far fa-circle nav-icon"></i>Social</router-link>
			</li>
			-->
		</ul>
	</li>
@stop

@section('content')
	<h3 class="text-center">@yield('title')</h3>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<router-link :to="{ name: 'home' }" active-class="disabled" exact>Home</router-link>
			</li>
			<!--li class="breadcrumb-item">
				<router-link :to="{ name: 'artists' }" active-class="disabled" exact>Artists</router-link>
			</li-->
			<li class="breadcrumb-item">
				<router-link :to="{ name: 'playlists' }" active-class="disabled" exact>Playlists</router-link>
			</li>
			<!--
			<li class="breadcrumb-item">
				<router-link :to="{ name: 'history' }" active-class="disabled" exact>History</router-link>
			</li>
			<li class="breadcrumb-item">
				<router-link :to="{ name: 'social' }" active-class="disabled" exact>Social</router-link>
			</li>
			-->
		</ol>
	</nav>

	<router-view></router-view>
@endsection

@section('vue')
    <script src="{{ mix('js/spotify.js') }}"></script>
@endsection