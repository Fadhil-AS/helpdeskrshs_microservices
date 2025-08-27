<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lacak Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\styleTicketing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\imageLacak.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\styleModal.css') }}">
</head>

<body>
    @include('Services.Ticketing.partials.navbarTicketing')
    @yield('containTicketing')
    @stack('scripts')
    @include('Services.Ticketing.lacakTicket.layouts.footerLacakTicketing')
</body>

</html>
