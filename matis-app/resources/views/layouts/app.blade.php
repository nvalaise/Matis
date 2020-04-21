<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Matis - @yield('title')</title>

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/materialize.css') }}">
    <script type="text/javascript" src="{{ mix('js/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/bootstrap.min.js') }}"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    
</head>
<body>

    <div id="app">

        @include('layouts.navbar')

        <div id="content" class="container-fluid">
            @yield('content')
        </div>

    </div>

    @yield('vue')

</body>
</html>