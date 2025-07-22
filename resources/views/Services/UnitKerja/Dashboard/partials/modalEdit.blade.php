<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
    data-url-template="{{ route('unitKerja.dashboard.update', ['id_complaint' => 'PLACEHOLDER']) }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-1">Isi Klarifikasi Unit</h6>
                            <small class="text-muted">ID: <span id="edit-id"></span></small>
                        </div>
                        <small>Status: <span id="edit-status-badge"></span> </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Pengaduan</label>
                        <input type="text" class="form-control" id="edit-judul" name="JUDUL_COMPLAINT" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                        <textarea class="form-control bg-light" rows="2" id="edit-deskripsi" readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Rangkuman Permasalahan</label>
                        <textarea class="form-control bg-light" rows="2" id="edit-permasalahan" readonly></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">File Pengaduan</label>
                        <div class="file-display-container" id="filePengaduanContainer">
                            <p class="text-muted m-0">Tidak ada file pengaduan.</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Petugas Evaluasi</label>
                            <input type="text" class="form-control" value="Admin Unit Kerja"
                                id="edit-petugas-evaluasi" name="PETUGAS_EVALUASI">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Evaluasi</label>
                            <input type="date" class="form-control" id="edit-tanggal-evaluasi" name="TGL_EVALUASI">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Klarifikasi Unit <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" name="klarifikasi_unit" id="edit-klarifikasi-unit"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">File Bukti Klarifikasi (Jika ada)</label>
                        <input class="form-control" type="file" name="file_bukti[]" id="edit-file-bukti" multiple>
                        <small class="form-text text-muted">Ukuran file maksimal: 2MB dan file dapat lebih dari satu.</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                        <button class="btn btn-simpan" type="submit">Simpan Klarifikasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
