<div class="container my-5 pt-2">
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
                    <tr>
                        <td><strong>1</strong></td>
                        <td>DIREKTUR UTAMA</td>
                        <td><i class="bi bi-telephone me-2"></i>081234567890</td>
                        <td><span class="badge bg-info">DIRUT</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>2</strong></td>
                        <td>DIREKTORAT MEDIK DAN PERAWATAN</td>
                        <td><i class="bi bi-telephone me-2"></i>081234567891</td>
                        <td><span class="badge bg-info">DIRMED</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>3</strong></td>
                        <td>DIREKTORAT SDM DAN PENDIDIKAN</td>
                        <td><i class="bi bi-telephone me-2"></i>081234567892</td>
                        <td><span class="badge bg-info">DIRSDM</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>4</strong></td>
                        <td>DIREKTORAT PERENCANAAN DAN KEUANGAN</td>
                        <td><i class="bi bi-telephone me-2"></i>081234567893</td>
                        <td><span class="badge bg-info">DIRKEU</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>5</strong></td>
                        <td>DIREKTORAT LAYANAN OPERASIONAL</td>
                        <td><i class="bi bi-telephone me-2"></i>081234567894</td>
                        <td><span class="badge bg-info">DIRUM</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditDireksi">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3 page-tabel">
            <nav aria-label="Page navigation example">
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
            </nav>
        </div>
    </div>
</div>
