@extends('Services.SPI.layouts.headingSPI')

<body>
    <!-- Navbar -->
    @include('Services.Humas.partials.navbar')

    {{-- error handle --}}
    @include('Services.SPI.partials.errorHandle')

    {{-- tabel --}}
    @include('Services.SPI.partials.tableSPI')

    {{-- form --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    @include('Services.SPI.layouts.detailSPI')
    <script src="{{ asset('assets/js/SPI/fungsiDetail.js') }}"></script>
    <script src="{{ asset('assets/js/SPI/fungsiFilter.js') }}"></script>
    <script src="{{ asset('assets/js/SPI/fungsiCari.js') }}"></script>

    <script>
        var detailUrlTemplate = "{{ route('spi.pelaporan-SPI.detail', ['id_complaint' => ':id']) }}";
        var storageBaseUrl = "{{ asset('storage') }}";
    </script>
</body>

</html>
