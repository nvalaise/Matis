@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div id="content" class="container">
<div class="mt-2">
	<div class="jumbotron">
		<h1 class="display-4">Hello, @if(Auth::check()) <i class="text-info"> {{ Auth::user()->name }} </i> @else<i>world</i>@endif !</h1>
		<p class="lead">Welcome on board with Matis, this beautiful app that aims to give you visibility over your applications.</p>
		<hr class="my-4">
		<p>We collect only the data provided by the imposed applications. Indeed, they open REST API for developpers so that we are able to collect it.</p>
		<p>By default, none of your info is stored unless you explicitally click on the <a href="#" class="btn btn-primary mx-2">Save</a> button.</p>
		<p class="lead">
			<a class="btn btn-success btn-lg" href="/auth" role="button">Choose your apps</a>
		</p>
	</div>
</div>
</div>
@endsection

@section('vue')
    <!--script src="/js/app.js"></script-->
@endsection