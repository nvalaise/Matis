@extends('layouts.dashboard')

@section('title', 'Deezer')

@section('asset')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
@endsection

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
@stop

@section('sub-content')
	<router-view></router-view>
@endsection

@section('vue')
    <script src="{{ mix('js/deezer.js') }}"></script>
@endsection