@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div>

	@if ( Session::has('flash_message') )
	<div class="row">
		<div class="col-12 alert {{ Session::get('flash_type') }}" role="alert">
			<p>{!! Session::get('flash_message') !!}</p>
		</div>
	</div>
	@endif

	<div class="row">
		<div class="col-12">
			<h2>
				Music
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<ul class="list-group" id="list-tab" role="tablist">
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<a class="collection-item" id="list-deezer-list" data-toggle="list" href="#list-deezer" role="tab" aria-controls="deezer">Deezer</a>
					@if(Auth::check() && Auth::user()->has('deezer')) 
					<span class="badge badge-primary badge-pill">Active</span>
					@endif
				</li>
				<li class="list-group-item">
					<a class="collection-item" id="list-spotify-list" data-toggle="list" href="#list-spotify" role="tab" aria-controls="spotify">Spotify</a>
				</li>
				<li class="list-group-item">
					<a class="collection-item" id="list-discogs-list" data-toggle="list" href="#list-discogs" role="tab" aria-controls="discogs">Discogs</a>
				</li>
			</ul>
		</div>
		<div class="col-8">
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active" id="list-deezer" role="tabpanel" aria-labelledby="list-deezer-list">
					<p>
						@if(Auth::check() && Auth::user()->has('deezer'))
							<a href="{{ route('auth.login', 'deezer') }}" class="btn btn-primary">Refresh</a>
							<a href="{{ route('auth.logout', 'deezer') }}" class="btn btn-danger">Logout</a>
						@else
							<a href="{{ route('auth.login', 'deezer') }}" class="btn btn-success">Connect</a>
						@endif
					</p>
					<p>
						Deezer is a French online music streaming service. It allows users to listen to music content from record labels, including Universal Music Group, Sony Music and Warner Music Group (owned by Deezer's parent company Access Industries) on various devices online or offline. Created in Paris, France, Deezer currently has 56 million licensed tracks in its library, with over 30,000 radio channels, 14 million monthly active users, and 7 million paid subscribers as of January 2019.[5] The service is available for Web, Android, iOS, Windows Mobile, BlackBerry OS and Windows, MacOS. 
					</p>
					<p class="text-right">
						<a class="btn btn-success" href="/deezer" role="button">Dashboard</a>
					</p>
				</div>

				<div class="tab-pane fade" id="list-spotify" role="tabpanel" aria-labelledby="list-spotify-list">
					<p>
						<a href="#" class="btn btn-primary">Save</a>
						<a href="#" class="btn btn-success">Connect</a>
						<a href="#" class="btn btn-danger">Logout</a>
					</p>

					<p>
						Spotify Technology S.A. is an international media services provider. It is legally domiciled in Luxembourg and is headquartered in Stockholm, Sweden.
					</p>
					<p>
						Founded in 2006, the company's primary business is providing an audio streaming platform, the "Spotify" platform, that provides DRM-restricted music, videos and podcasts from record labels and media companies. As a freemium service, basic features are free with advertisements or automatic music videos, while additional features, such as offline listening and commercial-free listening, are offered via paid subscriptions.
					</p>
					<p>
						Launched on October 2008, the Spotify platform provides access to over 50 million tracks. Users can browse by parameters such as artist, album, or genre, and can create, edit, and share playlists. Spotify is available in most of Europe and the Americas, Australia, New Zealand, and parts of Africa and Asia, and on most modern devices, including Windows, macOS, and Linux computers, and iOS, and Android smartphones and tablets.[8][9] As of February 2020, the company had 271 million monthly active users, including 124 million paying subscribers.
					</p>
					<p class="text-right">
						<a class="btn btn-success" href="/deezer" role="button">Dashboard</a>
					</p>
				</div>
				<div class="tab-pane fade" id="list-discogs" role="tabpanel" aria-labelledby="list-discogs-list">
					<p>
						<a href="#" class="btn btn-primary">Save</a>
						<a href="#" class="btn btn-success">Connect</a>
						<a href="#" class="btn btn-danger">Logout</a>
					</p>
					<p>
						Discogs (short for discographies) is a website and crowdsourced database of information about audio recordings, including commercial releases, promotional releases, and bootleg or off-label releases. The Discogs servers, currently hosted under the domain name discogs.com, are owned by Zink Media, Inc. and located in Portland, Oregon, US. While the site was originally created with a goal of becoming the largest online database of electronic music, there are now releases in all genres and on all formats on the site. In fact, after the database was opened to contributions from the public, rock music began to take over as the most prevalent genre. 
					</p>
					<p>
						Discogs currently contains over 11.6 million releases, by over 6 million artists, across over 1.3 million labels, contributed from over 456,000 contributor user accounts—with these figures constantly growing as users continually add previously unlisted releases to the site over time. 
					</p>
					<p class="text-right">
						<a class="btn btn-success" href="/deezer" role="button">Dashboard</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection