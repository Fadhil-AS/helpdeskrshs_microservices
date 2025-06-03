<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-labelledby="modalTambahAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start">
                <h6 class="modal-title" id="modalTambahAdminLabel">Tambah Admin Unit Kerja</h6>
                <small class="mt-1">Isi formulir berikut untuk menambahkan unit kerja baru</small>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" placeholder="Masukkan username">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Sementara</label>
                        <input type="password" class="form-control" placeholder="Masukkan password sementara">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIP</label>
                            <input type="text" class="form-control" placeholder="Masukkan NIP">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <input type="text" class="form-control" placeholder="Masukkan nomor telepon">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Spesial</label>
                        <input type="text" class="form-control" placeholder="Masukkan kode spesial">
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
