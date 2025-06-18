@extends('Services.Admin.Dashboard.layouts.headingAdmin')

<body>
    <!-- Navbar -->
    @include('Services.Humas.partials.navbar')

    <!-- Tabel Grafik -->
    @include('Services.Admin.Dashboard.partials.tabelGrafik')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <!-- PERBAIKAN: Tambahkan library utama Chart.js di sini -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> --}}

    <!-- Plugin datalabels harus dimuat setelah Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <!-- Data Grafik  -->
    <script src="{{ asset('assets/js/Admin/grafik.js') }}"></script>

</body>

</html>
