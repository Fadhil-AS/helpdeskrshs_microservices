<div class="modal fade" id="modalEditAdmin" tabindex="-1" aria-labelledby="modalEditAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body p-4">
                <h6 class="mb-1 fw-bold">Edit Admin Unit Kerja</h6>
                <small class="text-muted mb-3 d-block">No. Register: 2104_00000200</small>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" value="telkom">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" value="Mhs Telkom">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIP</label>
                            <input type="text" class="form-control" value="123456">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <input type="text" class="form-control" value="088888888">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit Kerja</label>
                        <select class="form-select">
                            <option selected disabled>Pilih unit kerja</option>
                            <option>Farmasi</option>
                            <option>IGD</option>
                            <option>Radiologi</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Status Validasi</label>
                        <select class="form-select">
                            <option>Tervalidasi</option>
                            <option>Belum Tervalidasi</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-simpan">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
