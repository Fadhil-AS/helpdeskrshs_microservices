@extends('Services.Humas.DataReferensi.layouts.headingDataReferensi')

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')

    @include('Services.Humas.DataReferensi.partials.log')

    <!-- Tabs -->
    <div class="container mt-5">
    @include('Services.Humas.DataReferensi.partials.tabsDireksi')

    <!-- Tab Content -->
        <div class="tab-content mt-4">
        <!-- Tabel 1 Klasifikasi Pengaduan & Jenis Media -->
        <div class="tab-pane fade show active" id="tab-klasifikasi" role="tabpanel" aria-labelledby="tab-klasifikasi-tab">


            {{-- tabel klasifikasi --}}
            @include('Services.Humas.DataReferensi.partials.klasifikasiPengaduan.tabelKlasifikasi')

            {{-- tabel jenis media --}}
            @include('Services.Humas.DataReferensi.partials.jenisMedia.tabelJenisMedia')
        </div>

        <!-- Tabel 2 Penyelesaian Pengaduan & Jenis Laporan -->
        <div class="tab-pane fade" id="tab-penyelesaian" role="tabpanel" aria-labelledby="tab-penyelesaian-tab">
            {{-- tabel penyelesaian pengaduan --}}
            @include('Services.Humas.DataReferensi.partials.penyelesaianPengaduan.penyelesaianPengaduan')

            {{-- tabel jenis laporan --}}
            @include('Services.Humas.DataReferensi.partials.jenisLaporan.jenisLaporan')
        </div>
    </div>

    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/Humas/dataReferensi/fungsiTabel.js') }}"></script>

    {{-- klasifikasi pengaduan --}}
    <script src="{{ asset('assets/js/Humas/dataReferensi/KlasifikasiPengaduan/klasifikasiHandler.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/dataReferensi/KlasifikasiPengaduan/fungsiEdit.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/dataReferensi/KlasifikasiPengaduan/fungsiDelete.js') }}"></script>

    {{-- jenis media --}}
    <script src="{{ asset('assets/js/Humas/dataReferensi/JenisMedia/jenisMediaHandler.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/dataReferensi/JenisMedia/fungsiEdit.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/dataReferensi/JenisMedia/fungsiDelete.js') }}"></script>

    {{-- penyelesaian pengaduan --}}
    <script src="{{ asset('assets/js/Humas/dataReferensi/PenyelesaianPengaduan/penyelesaianPengaduanHandler.js') }}">
    </script>
    <script src="{{ asset('assets/js/Humas/dataReferensi/PenyelesaianPengaduan/fungsiDelete.js') }}"></script>

    {{-- jenis laporan --}}
    <script src="{{ asset('assets/js/Humas/dataReferensi/JenisLaporan/jenisLaporanHandler.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/dataReferensi/JenisLaporan/fungsiDelete.js') }}"></script>
</body>

</html>
