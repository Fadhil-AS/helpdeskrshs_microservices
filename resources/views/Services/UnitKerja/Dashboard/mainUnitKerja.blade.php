@extends('Services.UnitKerja.Dashboard.layouts.headingUnitKerja')

<body>
    <!-- Navbar -->
    @include('Services.Humas.partials.navbar')

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
    @if (session('error_complaint_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var complaintIdWithError = '{{ session('error_complaint_id') }}';
                var buttonToClick = document.querySelector('.tombol-edit-klarifikasi[data-id="' + complaintIdWithError +
                    '"]');
                if (buttonToClick) {
                    buttonToClick.click();
                }
            });
        </script>
    @endif
</body>

</html>
