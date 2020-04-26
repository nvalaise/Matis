@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div id="content" class="container">
	<div class="jumbotron">
		<div class="d-inline-block">
			<h1 class="d-inline display-4"><span class="text-primary">M</span><span class="text-secondary">ATIS</span></h1>
			<h2 class="ml-4 d-inline"><i>_own your data</i></h2>
		</div>
			
		
		<p class="lead">Welcome on board with Matis. Through this website, you will be able to manage your data provided by your applications</p>
		<hr class="my-4">
		<p>We collect only the data provided by the different applications. Indeed, they open <i>REST API</i> so that we are able to display your data.</p>
		<p>By default, none of your data is stored unless you explicitally click on the <a href="#" class="btn btn-primary mx-2">Save</a> button.</p>
		<p class="lead">
			<a class="btn btn-success btn-lg" href="/auth" role="button">Get Started</a>
		</p>
	</div>

	<!-- TO DO List -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="ion ion-clipboard mr-1"></i>
          To Do List
        </h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
      	<p>What to promote a new application or feature (... or report a bug 🙃) ? Feel free to contact me: <i class="font-weight-bold">nicolas [dot] valaise [at] hotmail [dot] fr</i></p>
      	<p>Follow the developments on <a href="https://github.com/nvalaise/Matis">Github</a>.</p>

        <ul class="todo-list" data-widget="todo-list">
			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo2" id="todoCheck1" checked>
			  <label for="todoCheck1"></label>
			</div>
			<span class="text">First version online</span>
			<small class="badge badge-success"><i class="far fa-clock"></i> Done</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo2" id="todoCheck2" checked>
			  <label for="todoCheck2"></label>
			</div>
			<span class="text">Get Started with Deezer</span>
			<small class="badge badge-success"><i class="far fa-clock"></i> Done</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck3">
			  <label for="todoCheck3"></label>
			</div>
			<span class="text">Get Started with Discogs</span>
			<small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck4">
			  <label for="todoCheck4"></label>
			</div>
			<span class="text">Get Started with Spotify</span>
			<small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck5">
			  <label for="todoCheck5"></label>
			</div>
			<span class="text">Store data on Matis</span>
			<small class="badge badge-warning"><i class="far fa-clock"></i> 2 week</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck6">
			  <label for="todoCheck6"></label>
			</div>
			<span class="text">Create streaming playlist from Discogs collections</span>
			<small class="badge badge-warning"><i class="far fa-clock"></i> 4 week</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck7">
			  <label for="todoCheck7"></label>
			</div>
			<span class="text">Cross-application search</span>
			<small class="badge badge-warning"><i class="far fa-clock"></i> 4 week</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck8">
			  <label for="todoCheck8"></label>
			</div>
			<span class="text">Build an analytic platform</span>
			<small class="badge badge-danger"><i class="far fa-clock"></i> 5 week</small>
			</li>

			<li>
			<div  class="icheck-primary d-inline ml-2">
			  <input type="checkbox" value="" name="todo1" id="todoCheck9">
			  <label for="todoCheck9"></label>
			</div>
			<span class="text">Share my data with friends - Social platform</span>
			<small class="badge badge-danger"><i class="far fa-clock"></i> 6 week</small>
			</li>
        </ul>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection

@section('vue')
    <!--script src="/js/app.js"></script-->
@endsection