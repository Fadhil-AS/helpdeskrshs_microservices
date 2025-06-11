<div class="container rounded container-tabel my-5 pt-2">
    <!-- Header Box -->
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Dashboard Unit Kerja RSHS Bandung</h5>
        <p class="mb-0">Penanganan pengaduan dan klarifikasi unit kerja</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3 tombol-cari">
            <div class="d-flex flex-wrap gap-2 grup-tombol">
                <select class="selectpicker" data-style="btn-reset">
                    <option>Semua Status</option>
                    <option>Open</option>
                    <option>On Progress</option>
                    <option>Close</option>
                </select>
                <button class="btn btn-reset"><i class="bi bi-filter"></i> Reset</button>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari Pengaduan...">
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Media</th>
                        <th>Info Direksi</th>
                        <th>Status</th>
                        <th>Grading</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>202405_0000001</strong></td>
                        <td>Pelayanan Lambat di Poli Mata</td>
                        <td>Website</td>
                        <td>Mohon ditindaklanjuti segera</td>
                        <td><span class="badge bg-warning">On Progress</span></td>
                        <td><span class="badge bg-warning text-light">Kuning</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>202405_0000002</strong></td>
                        <td>Kesalahan Pemberian Obat</td>
                        <td>WhatsApp</td>
                        <td>Mohon ditindaklanjuti segera</td>
                        <td><span class="badge bg-warning text-light">On Progress</span></td>
                        <td><span class="badge bg-danger">Merah</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>202405_0000003</strong></td>
                        <td>Kesalahan Tagihan Rawat Inap</td>
                        <td>Instagram</td>
                        <td>Mohon ditindaklanjuti segera</td>
                        <td><span class="badge bg-danger-subtle text-danger">Close</span></td>
                        <td><span class="badge bg-warning text-light">Kuning</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal">
                                <i class="bi bi-eye me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="bi bi-pencil-square"></i>
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
