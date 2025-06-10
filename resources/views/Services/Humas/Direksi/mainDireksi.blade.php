@extends('Services.Humas.Direksi.layouts.headingDireksi')

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')

    {{-- tabel direksi --}}
    @include('Services.Humas.Direksi.partials.tabelDireksi')

    <!-- Modal Tambah Data Direksi -->
    @include('Services.Humas.Direksi.partials.modalTambahDireksi')

    <!-- Modal Edit Data Direksi -->
    @include('Services.Humas.Direksi.partials.modalEditDireksi')
    @include('Services.Humas.Direksi.partials.modalHapus')


    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/Humas/Direksi/modalEdit.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/Direksi/modalHapus.js') }}"></script>
</body>

</html>
