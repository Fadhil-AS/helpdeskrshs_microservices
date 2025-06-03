<div class="modal fade" id="modalTambahPengaduan" tabindex="-1" aria-labelledby="modalTambahPengaduanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPengaduanLabel">Tambah Pengaduan Baru</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Judul Pengaduan</label>
                            <input type="text" class="form-control" placeholder="Masukkan judul pengaduan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <input type="text" class="form-control" placeholder="Masukkan nomor telepon">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pelapor</label>
                            <input type="text" class="form-control" placeholder="Masukkan nama pelapor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Unit Kerja Tujuan</label>
                            <select class="form-select">
                                <option selected disabled>Pilih unit kerja</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Medrec (jika ada)</label>
                            <input type="text" class="form-control" placeholder="Masukkan nomor rekam medis">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Laporan</label>
                            <select class="form-select">
                                <option selected disabled>Pilih jenis laporan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Media Pengaduan</label>
                            <select class="form-select">
                                <option selected disabled>Pilih media</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Klasifikasi Pengaduan</label>
                            <select class="form-select">
                                <option selected disabled>Pilih klasifikasi pengaduan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                        <textarea class="form-control" rows="2" placeholder="Masukkan deskripsi pengaduan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rangkuman Permasalahan</label>
                        <textarea class="form-control" rows="2" placeholder="Masukkan rangkuman permasalahan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Pengaduan (jika ada)</label>
                        <input type="file" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                <button class="btn text-white btn-simpan">Tambah Pengaduan</button>
            </div>
        </div>
    </div>
</div>
