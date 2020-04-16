<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Matis</a>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Apps
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <a class="dropdown-item" href="/deezer">Deezer</a>
                    <a class="dropdown-item" href="#">Spotify</a>
                    <a class="dropdown-item" href="#">Bandcamp</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Youtube</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto mr-4 mt-2 mt-lg-0">
            @auth
            <li class="nav-item">
                <a class="nav-link" href="/auth">Account</a>
            </li>
            @endauth

            @guest
            <li class="nav-item">
                <a class="nav-link" href="/auth">Login</a>
            </li>
            @endguest

        </ul>

        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>