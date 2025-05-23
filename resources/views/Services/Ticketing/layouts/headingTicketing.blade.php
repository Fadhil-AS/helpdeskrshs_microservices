<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\styleTicketing.css') }}">
    <style>
    </style>
</head>

<body>
    @include('Services.Ticketing.partials.navbarTicketing')
    @yield('containTicketing')
    @include('Services.Ticketing.layouts.footerTicketing')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
