<div class="modal fade" id="modalTambahUnitKerja" tabindex="-1" aria-labelledby="modalTambahUnitKerjaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start">
                <h6 class="modal-title" id="modalTambahUnitKerjaLabel">Tambah Unit Kerja</h6>
                <small class="mt-1">Isi formulir berikut untuk menambahkan unit kerja baru</small>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit Kerja (Direksi)</label>
                        <select class="form-select">
                            <option>Unit Kerja A</option>
                            <option>Unit Kerja B</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Bagian</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama bagian">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Singular</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama singular">
                        <small class="text-muted">Jika kosong, akan menggunakan nama bagian</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Alternatif</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama alternatif (opsional)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-select">
                            <option selected>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                <button class="btn text-white btn-simpan">Tambah</button>
            </div>
        </div>
    </div>
</div>
