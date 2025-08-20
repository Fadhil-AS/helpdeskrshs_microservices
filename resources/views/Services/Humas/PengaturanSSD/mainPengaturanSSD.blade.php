@extends('Services.Humas.PengaturanSSD.layouts.headingPengaturanSSD')

<body>
    {{-- Navbar --}}
    @include('Services.Humas.partials.navbar')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops! Terjadi kesalahan validasi:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabel Kategori SSD --}}
    @include('Services.Humas.PengaturanSSD.partials.KategoriSSD.tabelKategoriSSD')

    <!-- Modal Tambah Kategori SSD -->
    @include('Services.Humas.PengaturanSSD.partials.KategoriSSD.modalTambahKategoriSSD')

    <!-- Modal Edit Kategori SSD -->
    @include('Services.Humas.PengaturanSSD.partials.KategoriSSD.modalEditKategoriSSD')

    <!-- Modal Hapus Kategori SSD -->
    @include('Services.Humas.PengaturanSSD.partials.KategoriSSD.modalHapusKategoriSSD')

    {{-- Tabel Pengaturan SSD --}}
    @include('Services.Humas.PengaturanSSD.partials.DataSSD.tabelPengaturanSSD')

    <!-- Modal Tambah Data SSD -->
    @include('Services.Humas.PengaturanSSD.partials.DataSSD.modalTambahPengaturanSSD')

    <!-- Modal Edit Data SSD -->
    @include('Services.Humas.PengaturanSSD.partials.DataSSD.modalEditPengaturanSSD')

    <!-- Modal Hapus Data SSD -->
    @include('Services.Humas.PengaturanSSD.partials.DataSSD.modalHapusPengaturanSSD')


    <script src="{{ asset('assets/js/Humas/navbar.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/Humas/PengaturanSSD/modalEdit.js') }}"></script>
    <script>
        window.ssdPageData = {
            searchUrl: "{{ route('humas.pengaturan-ssd-humas') }}",
            kategoriUpdateUrl: "{{ route('humas.pengaturan-ssd.kategori.update', ['kategori' => ':id']) }}",
            ssdUpdateUrl: "{{ route('humas.pengaturan-ssd.ssd.update', ['ssd' => ':id']) }}",
            kategoriDestroyUrl: "{{ route('humas.pengaturan-ssd.kategori.destroy', ['kategori' => ':id']) }}",
            ssdDestroyUrl: "{{ route('humas.pengaturan-ssd.ssd.destroy', ['ssd' => ':id']) }}"
        };
    </script>
    <script src="{{ asset('assets/js/Humas/PengaturanSSD/fungsiEdit.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/PengaturanSSD/modalHapus.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/PengaturanSSD/fungsiHapus.js') }}"></script>
    <script src="{{ asset('assets/js/Humas/PengaturanSSD/fungsiCari.js') }}"></script>
</body>

</html>
