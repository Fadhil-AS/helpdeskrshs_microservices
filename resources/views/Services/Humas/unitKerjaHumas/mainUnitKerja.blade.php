@extends('Services.Humas.unitKerjaHumas.layouts.headingUnitKerjaHumas')

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- table unit kerja humas --}}
    @include('Services.Humas.unitKerjaHumas.partials.unitKerjaHumas.tabelUKH')

    <!-- Modal Tambah Data unit kerja humas -->
    @include('Services.Humas.unitKerjaHumas.partials.UnitKerjaHumas.modalTambahUKH')

    <!-- Modal Edit Data Direksi -->
    @include('Services.Humas.unitKerjaHumas.partials.UnitKerjaHumas.modalEditUKH')

    {{-- tabel Admin unit kerja --}}
    @include('Services.Humas.unitKerjaHumas.partials.adminUKH.tabelAUKH')

    <!-- Modal Tambah Admin Unit Kerja -->
    @include('Services.Humas.unitKerjaHumas.partials.adminUKH.modalTambahAUKH')

    <!-- Modal Detail Admin Unit Kerja -->
    @include('Services.Humas.unitKerjaHumas.partials.adminUKH.modalDetailAUKH')

    <!-- Modal Edit Admin Unit Kerja -->
    @include('Services.Humas.unitKerjaHumas.partials.adminUKH.modalEditAUKH')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    {{-- Unit kerja --}}
    <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/fungsiTabel.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/fungsiModalEdit.js') }}"></script>

    {{-- Admin unit kerja --}}
    <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiModalTambah.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiModalDetail.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/fungsiModalEdit.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/UnitKerjaHumas/AUKH/filteringAUKH.js') }}"></script>

    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>
</body>

</html>
