<div class="modal fade" id="modalEditDireksi" tabindex="-1" aria-labelledby="modalEditDireksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editDireksiForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header flex-column align-items-start">
                    <h6 class="modal-title" id="modalEditDireksiLabel">Edit Data Direksi</h6>
                    <small class="mt-1" id="editDireksiIdLabel">ID: 1</small>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Direksi</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control" id="edit_no_tlpn" name="no_tlpn" value="">
                        <small class="text-muted">Masukkan nomor WhatsApp yang aktif untuk notifikasi</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <input type="text" class="form-control" value="" id="edit_ket" name="ket">
                        <small class="text-muted">Kode singkat untuk identifikasi direksi (dirut, dirmed, dirsdm,
                            dll)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
