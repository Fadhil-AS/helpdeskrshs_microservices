@extends('Services.UnitKerja.Dashboard.layouts.headingUnitKerja')

<body>
    <!-- Navbar -->
    @include('Services.UnitKerja.partials.navbarUnitKerja')

    {{-- error handle --}}
    @include('Services.UnitKerja.Dashboard.partials.errorHandle')

    <!-- Tabel Unit Kerja -->
    @include('Services.UnitKerja.Dashboard.partials.tabelUnitKerja')

    <!-- Modal Detail Pengaduan -->
    @include('Services.UnitKerja.Dashboard.partials.modalDetail')

    <!-- Modal Edit Pengaduan  -->
    @include('Services.UnitKerja.Dashboard.partials.modalEdit')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/UnitKerja/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/UnitKerja/modalDetail.js') }}"></script>
    <script src="{{ asset('assets/js/UnitKerja/modalEdit.js') }}"></script>
    @if (session('show_modal_on_error') && $errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var errorModal = new bootstrap.Modal(document.querySelector("{{ session('show_modal_on_error') }}"));
                errorModal.show();
            });
        </script>
    @endif
</body>

</html>
