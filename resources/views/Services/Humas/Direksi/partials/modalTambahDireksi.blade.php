<div class="modal fade" id="modalTambahDireksi" tabindex="-1" aria-labelledby="modalTambahDireksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start">
                <h6 class="modal-title" id="modalTambahDireksiLabel">Tambah Data Direksi</h6>
                <small class="mt-1">Isi formulir berikut untuk menambahkan data direksi baru</small>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Direksi</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama direksi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control" placeholder="Masukkan nomor telepon">
                        <small class="text-muted">Masukkan nomor WhatsApp yang aktif untuk notifikasi</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <input type="text" class="form-control"
                            placeholder="Masukkan keterangan (contoh: dirut, dirmed)">
                        <small class="text-muted">Kode singkat untuk identifikasi direksi (dirut, dirmed, dirsdm,
                            dll)</small>
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
