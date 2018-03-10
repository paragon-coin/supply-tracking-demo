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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <header class="authHeader authHeaderHolder">
        <div class="authHeaderWrapper">
            <a href="#" class="logoWhite"></a>
            <a target="_blank" href="{{ asset('help.pdf') }}" class="helpBtn">
                <span class="icoHolder">
                    <i class="material-icons">play_for_work</i>
                </span>
                <span class="caption">Help</span>
            </a>
        </div>
    </header>
    <main class="">
        <div class="loginPageHolder">
            <div class="particles" id="particles-js1"></div>
            <div class="left">
                <div class="content">
                    <div class="logoHolder">
                        <div class="logo"></div>
                        <div class="caption">
                            <div class="text">SUPPLY TRACKING DEMO</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="content">
                    <div class="signFormHolder">
                        <div class="signForm">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
@include('_partials.alert')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
