<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <!-- <link rel="manifest" href="/manifest.json"> -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" sizes="32x32" href="/img/icons/icon-32x32.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/img/icons/icon-72x72.png">
        <link rel="apple-touch-icon" sizes="128x128" href="/img/icons/icon-128x128.png">
        <link rel="apple-touch-icon" sizes="192x192" href="/img/icons/icon-192x192.png">
        <link rel="apple-touch-startup-image" href="/img/icons/icon-192x192.png"> -->


        <!-- favicon -->
        <!-- <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
        <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#006633">
        <meta name="msapplication-TileColor" content="#000000"> -->

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- FONTS -->

        <!-- ICONS -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>

        <!-- DEV DEPENDENCIES -->
        <link href="/css/app.css" rel="stylesheet" type="text/css">

        <script>
            window.App = {!! json_encode([
                'signedIn' => \Auth::check(),
                'user' => \Auth::user()
            ]) !!}
        </script>

    </head>
    <body>
        <div id="app"></div>
        <script src="/js/app.js"></script>
        <script src="/js/vue.js"></script>
    </body>
</html>

