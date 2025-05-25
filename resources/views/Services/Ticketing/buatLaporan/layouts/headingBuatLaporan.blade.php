<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/Ticketing/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Ticketing/style.css') }}">
</head>

<body>
    @include('Services.Ticketing.partials.navbarTicketing')
    @yield('containBuatLaporan')
    @include('Services.Ticketing.buatLaporan.layouts.fungsiFileLaporan')
    @include('Services.Ticketing.buatLaporan.layouts.footerBuatLaporan')
</body>

</html>
