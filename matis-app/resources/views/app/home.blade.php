@extends('layouts.app')

@section('title', 'Home')

@section('content')
	<div class="jumbotron">
		<h1 class="display-4">Hello, @if(Auth::check()) <i class="text-info"> {{ Auth::user()->name }} </i> @else<i>world</i>@endif !</h1>
		<p class="lead">Welcome on board with Matis, this beautiful app that aims to give you visibility over the apps you use.</p>
		<hr class="my-4">
		<p>We collect only the data provided by the imposed applications. Indeed, they open REST API for developpers so that we are able to collect it. By default, none of your info is stored unless you explicitally click on the <a href="#" class="btn btn-primary">Save</a> button.</p>
		<p class="lead">
		<a class="btn btn-success btn-lg" href="/auth" role="button">Choose your apps</a>
		</p>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<h2>
					Music
				</h2>
			</div>
			
		</div>
		<div class="row">

			<div class="col-4">
			<div class="list-group" id="list-tab" role="tablist">
				<a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Deezer</a>
				<a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Spotify</a>
				<a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Discogs</a>
				<a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Bandcamp</a>
			</div>

			</div>
			<div class="col-8">
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
						<p>
							<a class="btn btn-success btn-lg" href="/deezer" role="button">Dashboard</a>
						</p>
						<p>
							Deezer is a French online music streaming service. It allows users to listen to music content from record labels, including Universal Music Group, Sony Music and Warner Music Group (owned by Deezer's parent company Access Industries) on various devices online or offline. Created in Paris, France, Deezer currently has 56 million licensed tracks in its library, with over 30,000 radio channels, 14 million monthly active users, and 7 million paid subscribers as of January 2019.[5] The service is available for Web, Android, iOS, Windows Mobile, BlackBerry OS and Windows, MacOS. 
						</p>
					</div>
					<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
						<p>
							Spotify Technology S.A. is an international media services provider. It is legally domiciled in Luxembourg and is headquartered in Stockholm, Sweden.
						</p>
						<p>
							Founded in 2006, the company's primary business is providing an audio streaming platform, the "Spotify" platform, that provides DRM-restricted music, videos and podcasts from record labels and media companies. As a freemium service, basic features are free with advertisements or automatic music videos, while additional features, such as offline listening and commercial-free listening, are offered via paid subscriptions.
						</p>
						<p>
							Launched on October 2008, the Spotify platform provides access to over 50 million tracks. Users can browse by parameters such as artist, album, or genre, and can create, edit, and share playlists. Spotify is available in most of Europe and the Americas, Australia, New Zealand, and parts of Africa and Asia, and on most modern devices, including Windows, macOS, and Linux computers, and iOS, and Android smartphones and tablets.[8][9] As of February 2020, the company had 271 million monthly active users, including 124 million paying subscribers.
						</p>
					</div>
					<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>
					<div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">...</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('vue')
    <!--script src="/js/app.js"></script-->
@endsection