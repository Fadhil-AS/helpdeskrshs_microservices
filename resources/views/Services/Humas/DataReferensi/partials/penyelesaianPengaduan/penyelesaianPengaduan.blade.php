<div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
    <h5 class="mb-1">Penyelesaian Pengaduan</h5>
    <p class="mb-0">Kelola daftar jenis penyelesaian pengaduan yang digunakan dalam sistem</p>
</div>
<div class="bg-white p-3 rounded-bottom shadow-sm">
    <div class="d-flex flex-column flex-md-row gap-2 align-items-start mb-3" style="max-width: 100%;">
        <input type="text" class="form-control" placeholder="Masukkan Jenis Penyelesaian Pengaduan Baru"
            style="max-width: 400px;">
        <button class="btn btn-tambah-pengaduan text-white">
            <i class="bi bi-plus-circle me-1"></i> Tambah
        </button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID Penyelesaian</th>
                    <th>Penyelesaian Pengaduan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>20250415000001</strong></td>
                    <td class="text-uppercase">
                        <span class="editable-text">SUDAH DIBERIKAN SANKSI</span>
                        <input type="text" class="form-control form-control-sm editable-input d-none"
                            value="SUDAH DIBERIKAN SANKSI">
                    </td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>
                        <div class="view-mode-actions d-inline-block">
                            <a href="#" class="btn-inline-edit me-2"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="btn-inline-delete text-danger"><i class="bi bi-trash"></i></a>
                        </div>
                        <div class="edit-mode-actions d-inline-block d-none">
                            <a href="#" class="btn-inline-save text-success me-2"><i
                                    class="bi bi-check-lg fs-5"></i></a>
                            <a href="#" class="btn-inline-cancel text-danger"><i class="bi bi-x-lg fs-5"></i></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>20250415000002</strong></td>
                    <td class="text-uppercase">
                        <span class="editable-text">DIBINA</span>
                        <input type="text" class="form-control form-control-sm editable-input d-none" value="DIBINA">
                    </td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>
                        <div class="view-mode-actions d-inline-block">
                            <a href="#" class="btn-inline-edit me-2"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="btn-inline-delete text-danger"><i class="bi bi-trash"></i></a>
                        </div>
                        <div class="edit-mode-actions d-inline-block d-none">
                            <a href="#" class="btn-inline-save text-success me-2"><i
                                    class="bi bi-check-lg fs-5"></i></a>
                            <a href="#" class="btn-inline-cancel text-danger"><i class="bi bi-x-lg fs-5"></i></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3 page-tabel">
        <nav aria-label="Page navigation example">
            <ul class="pagination mb-0">
                <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span
                            aria-hidden="true">&laquo;</span></a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span
                            aria-hidden="true">&raquo;</span></a></li>
            </ul>
        </nav>
    </div>
</div>
