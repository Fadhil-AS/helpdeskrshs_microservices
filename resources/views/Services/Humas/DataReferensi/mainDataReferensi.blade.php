@extends('Services.Humas.DataReferensi.layouts.headingDataReferensi')

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')

    <!-- Tabs -->
    @include('Services.Humas.DataReferensi.partials.tabsDireksi')

    <!-- Tab Content -->
    <div class="tab-content container my-5 pt-2">
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

    <script src="{{ asset('assets/js/Humas/dataReferensi/fungsiTabel.js') }}"></script>

    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
</body>

</html>
