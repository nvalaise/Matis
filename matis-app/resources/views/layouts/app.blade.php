<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Matis - @yield('title')</title>

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <script type="text/javascript" src="{{ mix('js/bootstrap.min.js') }}"></script>

</head>
<body>
    @include('layouts.navbar')

    <div id="app">
        @section("app")

        <div class="container-fluid">
            @yield('content')
        </div>

        @show
    </div>

    @yield('vue')

</body>
</html>