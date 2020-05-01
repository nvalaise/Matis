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

<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">
      <i class="fas fa-edit"></i>
      Music
    </h3>
  </div>
  <div class="card-body">
    <h4>Application available</h4>
    <div class="row">
      <div class="col-5 col-sm-3">
        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
          <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Deezer</a>
          <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Spotify</a>
          <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Discogs</a>
        </div>
      </div>
      <div class="col-7 col-sm-9">
        <div class="tab-content" id="vert-tabs-tabContent">
          <div class="tab-pane text-left fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1">
            <div class="row d-flex justify-content-between">
            	@if(Auth::check() && Auth::user()->has('deezer'))
      					<a href="{{ route('auth.login', 'deezer') }}" class="btn btn-primary">Refresh</a>
      					<a class="btn btn-success" href="/deezer" role="button">Dashboard</a>
      				@else
      					<a href="{{ route('auth.login', 'deezer') }}" class="btn btn-success">Connect</a>
      				@endif

            </div>
            <hr>
            <div class="row">
             Deezer is a French online music streaming service. It allows users to listen to music content from record labels, including Universal Music Group, Sony Music and Warner Music Group (owned by Deezer's parent company Access Industries) on various devices online or offline. Created in Paris, France, Deezer currently has 56 million licensed tracks in its library, with over 30,000 radio channels, 14 million monthly active users, and 7 million paid subscribers as of January 2019.[5] The service is available for Web, Android, iOS, Windows Mobile, BlackBerry OS and Windows, MacOS.             	
            </div>
          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
            <div class="row d-flex justify-content-between">
              @if(Auth::check() && Auth::user()->has('spotify'))
                <a href="{{ route('auth.login', 'spotify') }}" class="btn btn-primary">Refresh</a>
                <a class="btn btn-success" href="/spotify" role="button">Dashboard</a>
              @else
                <a href="{{ route('auth.login', 'spotify') }}" class="btn btn-success">Connect</a>
              @endif
            </div>
            <hr>
            <div class="row">
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
          </div>
          <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3">
          	<div class="row d-flex justify-content-between">
              @if(Auth::check() && Auth::user()->has('discogs'))
                <a href="{{ route('auth.login', 'discogs') }}" class="btn btn-primary">Refresh</a>
                <a class="btn btn-success" href="/discogs" role="button">Dashboard</a>
              @else
                <a href="{{ route('auth.login', 'discogs') }}" class="btn btn-success">Connect</a>
              @endif
            </div>
            <hr>
             <p>
				Discogs (short for discographies) is a website and crowdsourced database of information about audio recordings, including commercial releases, promotional releases, and bootleg or off-label releases. The Discogs servers, currently hosted under the domain name discogs.com, are owned by Zink Media, Inc. and located in Portland, Oregon, US. While the site was originally created with a goal of becoming the largest online database of electronic music, there are now releases in all genres and on all formats on the site. In fact, after the database was opened to contributions from the public, rock music began to take over as the most prevalent genre. 
			</p>
			<p>
				Discogs currently contains over 11.6 million releases, by over 6 million artists, across over 1.3 million labels, contributed from over 456,000 contributor user accounts—with these figures constantly growing as users continually add previously unlisted releases to the site over time. 
			</p>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.card -->
</div>
@endsection