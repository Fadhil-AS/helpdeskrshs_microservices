<div class="modal fade" id="modalEditUnitKerja" tabindex="-1" aria-labelledby="modalEditUnitKerjaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start">
                <h6 class="modal-title" id="modalEditUnitKerjaLabel">Edit Unit Kerja</h6>
                <small class="mt-1" id="edit-id-bagian-display"></small>
            </div>
            <form id="formEditUnitKerja" method="POST" action="">
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit_nama_bagian">Nama Bagian</label>
                        <input id="edit_nama_bagian" type="text" class="form-control" name="NAMA_BAGIAN" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_singular" class="form-label fw-bold">Nama Singular</label>
                        <input id="edit_nama_singular" type="text" class="form-control" name="NAMA_BAGIAN_SINGULAR"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_alternatif" class="form-label fw-bold">Nama Alternatif</label>
                        <input id="edit_nama_alternatif" type="text" class="form-control" name="NAMA_ALTERNATIF">
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label fw-bold">Status</label>
                        <select id="edit_status" class="form-select" name="STATUS" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
