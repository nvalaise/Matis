<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="/" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="/auth" class="nav-link">Auth</a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Boards
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="/deezer">Deezer</a>
        <a class="dropdown-item" href="/spotify">Spotify</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">Next...</a>
      </div>
    </li>
  </ul>

  <!-- SEARCH FORM -->
  <form class="form-inline ml-3 my-auto">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Terms</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">About</a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  
<!-- Brand Logo -->
<a href="/" class="brand-link">
  <span class="brand-text font-weight-light">Matis</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  @auth
  <div class="user-panel mt-3 pb-3 mb-3">
    <div class="row">
      <div class="info">
        <a href="#" class="d-block"><span class="text-danger">#matis_id:</span> {{ Auth::user()->pseudo() }}</a>
      </div>
    </div>
    <div class="row d-flex justify-content-center">
      <p class="">
        <a href="{{ route('auth.logout', 'deezer') }}" class="btn btn-sm btn-danger">Logout</a>
      </p>
    </div>
  </div>
  @endauth

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="/" class="nav-link @if (Request::path() == '/') active @endif">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Home
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="/auth" class="nav-link @if (Request::path() == 'auth') active @endif">
          <i class="nav-icon fas fa-users"></i>
          <p>
            Auth
          </p>
        </a>
      </li>
      @section('sidebar-deezer')
      <li class="nav-item">
        <a href="/deezer" class="nav-link">
          <i class="nav-icon fas fa-code"></i>
          <p>
            Deezer
            @if(Auth::check() && Auth::user()->has('deezer'))
            <span class="right badge bg-green">Active</span>
            @endif
          </p>
        </a>
      </li>
      @show
      @section('sidebar-spotify')
      <li class="nav-item">
        <a href="/spotify" class="nav-link">
          <i class="nav-icon fas fa-code"></i>
          <p>
            Spotify
            @if(Auth::check() && Auth::user()->has('spotify'))
            <span class="right badge bg-green">Active</span>
            @endif
          </p>
        </a>
      </li>
      @show
      <li class="nav-item">
        <a href="#" class="nav-link @if (Request::path() == 'terms') active @endif">
          <i class="nav-icon far fa-plus-square"></i>
          <p>
            Terms
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link @if (Request::path() == 'terms') active @endif">
          <i class="nav-icon far fa-plus-square"></i>
          <p>
            About
          </p>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>