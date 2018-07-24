<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
              integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
              crossorigin="anonymous"></script>


</head>
<body>
    <div id="app">
        <!-- @include('partials.header') -->
        <!-- @include('partials.headerspare') -->
        <div class="container">
        @yield('content')
        </div>
    </div>

    <!-- Scripts -->

    <script src="{{ asset('js/onlineorder.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
