@extends('layouts.dashboard')

@section('title', 'Deezer')

@section('sub-navbar-content')
<li class="tab">
	<router-link :to="{ name: 'home' }">Home</router-link>
</li>
<li class="tab">
	<router-link :to="{ name: 'playlists' }">Playlists</router-link>
</li>
<li class="tab">
	<router-link :to="{ name: 'social' }">Social</router-link>
</li>
<li class="tab">
	<router-link :to="{ name: 'history' }">History</router-link>
</li>
<li class="tab">
	<router-link :to="{ name: 'social' }">Social</router-link>
</li>
@stop

@section('sub-content')
	<router-view></router-view>
@endsection

@section('vue')
    <script src="{{ mix('js/deezer.js') }}"></script>
@endsection