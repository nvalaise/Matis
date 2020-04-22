<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Matis - @yield('title')</title>

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/materialize.css') }}">
    <script type="text/javascript" src="{{ mix('js/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/bootstrap.min.js') }}"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    @yield('asset')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>


    
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