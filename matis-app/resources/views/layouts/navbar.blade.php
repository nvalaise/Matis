<nav class="nav-extended" id="navbar">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo">Matis</a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            @auth
            <li><a href="/auth">Account</a></li>
            @endauth

            @guest
            <li><a href="/auth">Login</a></li>
            @endguest

            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
    </div>
    
    @yield('sub-navbar')
</nav>

<ul class="sidenav" id="mobile-demo">
    @auth
    <li><a href="/auth">Account</a></li>
    @endauth

    @guest
    <li><a href="/auth">Login</a></li>
    @endguest

    <li><a href="badges.html">Components</a></li>
    <li><a href="collapsible.html">JavaScript</a></li>
</ul>
