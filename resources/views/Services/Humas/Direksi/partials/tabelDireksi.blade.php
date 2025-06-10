<div class="container my-5 pt-2">
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
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Data Direksi RSHS Bandung</h5>
        <p class="mb-0">Kelola data kontak direksi untuk sistem notifikasi</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 grup-tombol">
                <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                    data-bs-target="#modalTambahDireksi">
                    <i class="bi bi-plus-circle"></i> Tambah Data Direksi
                </button>

            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari Direksi...">
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th>ID</th>
                        <th>Nama Direksi</th>
                        <th>Nomor Telepon</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allDireksi as $direksi)
                        <tr>
                            <td><strong>{{ $direksi->ID_DIREKSI }}</strong></td>
                            <td>{{ $direksi->NAMA }}</td>
                            <td><i class="bi bi-telephone me-2"></i>{{ $direksi->NO_TLPN }}</td>
                            <td><span class="badge bg-info">{{ $direksi->KET }}</span></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi"
                                    data-id="{{ $direksi->ID_DIREKSI }}" data-nama="{{ $direksi->NAMA }}"
                                    data-no_tlpn="{{ $direksi->NO_TLPN }}" data-ket="{{ $direksi->KET }}">
                                    <i class="bi bi-pencil-square me-2"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal"
                                    data-id="{{ $direksi->ID_DIREKSI }}" data-nama="{{ $direksi->NAMA }}">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3 page-tabel">
            {{-- <nav aria-label="Page navigation example">
                <ul class="pagination mb-0">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav> --}}
            {{ $allDireksi->links() }}
        </div>
    </div>
</div>
