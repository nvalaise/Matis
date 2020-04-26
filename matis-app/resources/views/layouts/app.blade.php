<head>
    <meta charset="utf-8">

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Matis - @yield('title')</title>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- adminlte external dependencies -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Javascript -->
    <script type="text/javascript" src="{{ mix('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/adminlte.min.js') }}"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/adminlte.css') }}">

    @yield('asset')
    
</head>
<body>

    <div id="app" class="wrapper">

        @include('layouts.navbar')

        <div id="content" class="content-wrapper px-4 py-2">
            @yield('content')
        </div>

    </div>

    @yield('vue')

</body>
</html>