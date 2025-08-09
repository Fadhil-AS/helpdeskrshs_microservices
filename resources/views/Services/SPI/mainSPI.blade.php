@extends('Services.SPI.layouts.headingSPI')

<body>
    <!-- Navbar -->
    @include('Services.Humas.partials.navbar')

    {{-- error handle --}}
    @include('Services.SPI.partials.errorHandle')

    {{-- tabel --}}
    @include('Services.SPI.partials.tableSPI')

    {{-- form --}}
    @include('Services.SPI.layouts.detailSPI')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/SPI/fungsiDetail.js') }}"></script>
</body>

</html>
