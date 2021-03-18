<!DOCTYPE html>
<html>
<head>
    <title>Scrabble - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/gif" sizes="16x16">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/photoupload.css') }}"/>

    @if(config('app.env')=='local')
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
    @else
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
              integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
              crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}"/>
    <script src="{{ asset('js/photoupload.js') }}" defer></script>
    @yield('styles')

</head>
<body>

{{--<div class="container">--}}
{{--    <div class="row ">--}}
{{--        <div class="media">--}}
{{--            <div class="media-left media-middle">--}}
{{--                <img src="{{ asset('img/logo.jpg') }}" class="logo" width="550" height="550">--}}
{{--            </div>--}}

@yield('content')


{{--        </div>--}}
{{--    </div>--}}
@yield('scripts')
</body>
</html>
