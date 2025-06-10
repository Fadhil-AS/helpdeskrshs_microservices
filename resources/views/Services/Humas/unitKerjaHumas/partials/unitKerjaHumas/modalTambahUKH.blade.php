<div class="modal fade" id="modalTambahUnitKerja" tabindex="-1" aria-labelledby="modalTambahUnitKerjaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start">
                <h6 class="modal-title" id="modalTambahUnitKerjaLabel">Tambah Unit Kerja</h6>
                <small class="mt-1">Isi formulir berikut untuk menambahkan unit kerja baru</small>
            </div>
            <form method="POST" action="{{ route('humas.unit-kerja-humas.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="parentUnitKerja" class="form-label fw-bold" for="ID_BAGIAN">Pilih Induk Unit
                            Kerja</label>
                        <select id="parentUnitKerja" name="id_parent_bagian" class="form-select" name="ID_BAGIAN"
                            required>
                            <option value="" disabled selected>-- Pilih induk unit kerja --</option>
                            @foreach ($parents as $parentUnit)
                                <option value="{{ $parentUnit->ID_BAGIAN }}">
                                    {{ $parentUnit->ID_BAGIAN }} - {{ $parentUnit->NAMA_BAGIAN }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="NAMA_BAGIAN">Nama Bagian</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama bagian" name="NAMA_BAGIAN"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="NAMA_BAGIAN_SINGULAR">Nama Singular</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama singular"
                            name="NAMA_BAGIAN_SINGULAR" required>
                        <small class="text-muted">Jika kosong, akan menggunakan nama bagian</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="NAMA_ALTERNATIF">Nama Alternatif</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama alternatif (opsional)"
                            name="NAMA_ALTERNATIF">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="STATUS">Status</label>
                        <select class="form-select" name="STATUS" required>
                            <option value="" disabled selected>-- Pilih status --</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
