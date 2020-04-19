@extends('layouts.app')

@section('title', 'Deezer')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs">
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'home' }">Home</router-link>
							</li>

							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'playlists' }">Playlists</router-link>
							</li>
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'social' }">Social</router-link>
							</li>
							<li class="nav-item">
								<router-link class="nav-link" :to="{ name: 'history' }">History</router-link>
							</li>
            				<li class="nav-item ml-auto mr-0">
               					<a class="nav-link" href="/auth">Deezer</a>
            				</li>
            			</ul>
					</div>
					<div class="card-body">
						<div id="app" >
							<router-view></router-view>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('vue')
    <script src="{{ mix('js/deezer.js') }}"></script>
@endsection