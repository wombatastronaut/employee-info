<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Employee Info</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ url('css/app.css') }}">
    </head>
    <body>
        <div id="app">
            <div class="flex-center position-ref full-height">
                <!-- @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>
                            <a href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>
                @endif -->

                <div class="content">
                    <employees-table></employees-table>
                </div>
            </div>
        </div>
        @routes
        <script src="{{ url('js/app.js') }}"></script>
    </body>
</html>
