<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Matis - @yield('title')</title>

    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script href="/js/bootstrap.js"></script>

</head>
<body>
    @include('layouts.navbar')

    <div id="app">
        @section("app")

        <div class="container-fluid mt-2">
            @yield('content')
        </div>

        @show
    </div>

    @yield('vue')

</body>
</html>