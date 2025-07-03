@extends('Services.Humas.Pelaporan.layouts.headingPelaporan')
@section('containPelaporHumas')

    <div class="container rounded container-tabel my-5 pt-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any() && session('showModal'))
            {{-- Display validation errors if modal was intended to show --}}
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops! Ada kesalahan validasi:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Header Box -->
        <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
            <h5 class="mb-1">Sistem Informasi Pengaduan RSHS Bandung</h5>
            <p class="mb-0">Manajemen pengaduan dan tindak lanjut humas</p>
        </div>

        <!-- Filter & Action -->
        <div class="bg-white p-3 rounded-bottom shadow-sm">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
                <div class="d-flex flex-wrap gap-2 grup-tombol">
                    <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                        data-bs-target="#modalTambahPengaduan">
                        <i class="bi bi-plus-circle"></i> Tambah Pengaduan Baru
                    </button>
                    <form action="{{ route('humas.pelaporan-humas') }}" method="GET" id="filterForm"
                        class="d-flex flex-wrap gap-2">

                        <select class="form-select" name="status" id="filterStatus" style="width: 170px;">
                            <option value="">Semua Status</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="On Progress" {{ request('status') == 'On Progress' ? 'selected' : '' }}>On
                                Progress</option>
                            <option value="Menunggu Konfirmasi"
                                {{ request('status') == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi
                            </option>
                            <option value="Close" {{ request('status') == 'Close' ? 'selected' : '' }}>Close</option>
                            <option value="Banding" {{ request('status') == 'Banding' ? 'selected' : '' }}>Banding</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" id="resetFilter"
                            data-url="{{ route('humas.pelaporan-humas') }}"><i class="bi bi-arrow-counterclockwise"></i>
                            Reset</button>

                    </form>
                </div>
                <form action="{{ route('humas.pelaporan-humas') }}" method="GET">
                    <div class="input-group" style="width: 250px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Cari Pengaduan..."
                            name="search" id="search-input" value="{{ request('search') }}">
                    </div>
                </form>
            </div>

            <div id="tabel-pengaduan-container">
                @include('Services.Humas.Pelaporan.partials.tabelDataComplaint', [
                    'dataComplaint' => $dataComplaint,
                ])
            </div>

            
        </div>
    </div>

    <script>
        var detailUrlTemplate = "{{ route('humas.pelaporan-humas.detail', ['id_complaint' => ':id']) }}";
        var storageBaseUrl = "{{ asset('storage') }}";
        var updateUrlTemplate = "{{ route('humas.pelaporan-humas.update', ['id_complaint' => ':id']) }}";
        var searchUrl = "{{ route('humas.pelaporan-humas') }}";
    </script>
@endsection
