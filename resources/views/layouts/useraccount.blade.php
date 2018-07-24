<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
     <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <div id="app">
        @include('partials.nav')
        @include('partials.headerAccount')
        @yield('content')
    </div>

    <!-- Scripts -->

     <script type="text/javascript" src="{{ asset('js/onlineorder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>


</body>
</html>
