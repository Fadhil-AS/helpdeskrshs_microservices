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


    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
