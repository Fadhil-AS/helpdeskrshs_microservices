@extends('Services.Humas.DataNomor.layouts.headingDataNomor')

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')

    {{-- Tabel Data Nomor --}}
    @include('Services.Humas.DataNomor.partials.tabelDataNomor')

    <!-- Modal Edit Data Nomor -->
    @include('Services.Humas.DataNomor.partials.modalEditDataNomor')

    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/Humas/DataNomor/modalEdit.js') }}"></script>
</body>

</html>
