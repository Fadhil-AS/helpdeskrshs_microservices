<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Daftar Pelaporan Humas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>

    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/Humas/Pelaporan/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Humas/Pelaporan/modalEdit.css') }}">
</head>

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')

    @yield('containPelaporHumas')

    <!-- Modal Tambah Pengaduan -->
    @include('Services.Humas.Pelaporan.partials.tambahPelaporan')

    <!-- Modal Detail Pengaduan -->
    @include('Services.Humas.Pelaporan.partials.detailPelaporan')

    <!-- Modal Edit Pengaduan -->
    @include('Services.Humas.Pelaporan.partials.editPelaporan')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/Humas/Pelaporan/fungsiTabel.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/Pelaporan/filteringPelapor.JS') }}"></script>
    <script src="{{ asset('assets/js/Humas/Pelaporan/fungsiModalTambah.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/Pelaporan/fungsiModalDetail.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/Pelaporan/fungsiModalEdit.js') }}"></script>

    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

</body>

</html>
