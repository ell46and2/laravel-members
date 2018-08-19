<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="app js-app">
        @include('layouts.partials._site-head')
        @include('layouts.partials._site-nav')

        <main class="main">
            @include('layouts.partials._user-bar')

            <div class="flow-vertical--3">
                @if(strpos(Request::url(), 'dashboard') === false)
                   @include('layouts.partials._messages-alert') 
                @endif
                
                
                @yield('content')
            </div>

            @include('layouts.partials._site-foot')
        </main>
       
        @include('layouts.partials._notification-panel')

        <attachment-modal></attachment-modal>
        <confirmation></confirmation>
        <flash-message
            :success="{{ json_encode(Session::get('success')) }}"
            :error="{{ json_encode(Session::get('error')) }}"
        ></flash-message>
    </div>          
        
</body>
</html>
