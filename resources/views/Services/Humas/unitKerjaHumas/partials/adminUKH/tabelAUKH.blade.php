<div class="container my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Admin Unit Kerja RSHS Bandung</h5>
        <p class="mb-0">Kelola data unit kerja, struktur organisasi, dan admin unit kerja</p>
    </div>
    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 grup-tombol">
                <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                    data-bs-target="#modalTambahAdmin">
                    <i class="bi bi-plus-circle"></i> Tambah Admin Unit Kerja
                </button>
                <select class="selectpicker" data-style="btn-reset">
                    <option>Semua Unit</option>
                    <option>Unit A</option>
                    <option>Unit B</option>
                    <option>Unit C</option>
                </select>
                <select class="selectpicker" data-style="btn-reset">
                    <option>Semua Status</option>
                    <option>Tervalidasi</option>
                    <option>Belum Tervalidasi</option>
                </select>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari Unit Kerja...">
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th>No Register</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Unit Kerja</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th>Tanggal Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>2104_00000200</strong></td>
                        <td>telkom</td>
                        <td>Mhs Telkom</td>
                        <td>Admin</td>
                        <td>123456</td>
                        <td><span class="badge bg-success">Tervalidasi</span></td>
                        <td>21 April 2021</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetailAdmin">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditAdmin">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <i class="bi bi-trash"></i>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>2104_00000201</strong></td>
                        <td>admin_farmasi</td>
                        <td>Admin Farmasi</td>
                        <td>Admin</td>
                        <td>234567</td>
                        <td><span class="badge bg-success">Tervalidasi</span></td>
                        <td>10 Mei 2022</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetailAdmin">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditAdmin">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <i class="bi bi-trash"></i>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>2104_00000202</strong></td>
                        <td>admin_igd</td>
                        <td>Admin IGD</td>
                        <td>Admin</td>
                        <td>345678</td>
                        <td><span class="badge bg-warning">Belum Tervalidasi</span></td>
                        <td>05 Jan 2023</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetailAdmin">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditAdmin">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <i class="bi bi-trash"></i>
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
