<!doctype html>
<html ng-app="Mezzo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Mezzo</title>
    <base href="/">

    <link href="{{ cockpit_asset('/css/app.css') }}" rel='stylesheet' type="text/css" >
    <link href="{{ cockpit_asset('/css/auth.css') }}" rel='stylesheet' type="text/css" >

    <!-- CSS -->
</head>
<body class="sidebar-pinned">

@include('cockpit::layouts.auth.errors')

<!-- Content -->
<div class="container auth-container">
    @yield('content')
</div>
<!-- Content -->

<!-- JavaScript -->
</body>
</html>