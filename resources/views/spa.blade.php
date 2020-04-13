
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPA</title>

    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="" rel="stylesheet">
<link href="" rel="stylesheet">


</head>
<body>
<v-app id="app">
    <app></app>
</v-app>
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
