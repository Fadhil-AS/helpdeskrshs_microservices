<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-labelledby="modalTambahAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-start">
                <h6 class="modal-title" id="modalTambahAdminLabel">Tambah Admin Unit Kerja</h6>
                <small class="mt-1">Isi formulir berikut untuk menambahkan unit kerja baru</small>
            </div>
            <form method="POST" action="{{ route('humas.user-complaint.store') }}" id="formTambahAdmin">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="USERNAME">Username</label>
                        <input type="text" class="form-control @error('USERNAME') is-invalid @enderror"
                            placeholder="Masukkan username" name="USERNAME" required>
                        @error('USERNAME')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="PASSWORD">Password Sementara</label>
                        <input type="password" class="form-control @error('PASSWORD') is-invalid @enderror"
                            placeholder="Masukkan password sementara" name="PASSWORD" required>
                        @error('PASSWORD')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="NAME">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama lengkap" name="NAME"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="ID_BAGIAN">Unit Kerja</label>
                        <select class="form-select" name="ID_BAGIAN" required>
                            <option selected disabled>--- Pilih unit kerja ---</option>
                            @foreach ($unitsForDropdown as $unit)
                                <option value="{{ $unit->ID_BAGIAN }}">
                                    {{ $unit->ID_BAGIAN }} - {{ $unit->NAMA_BAGIAN }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="NIP">NIP</label>
                            <input type="text" class="form-control" placeholder="Masukkan NIP" name="NIP"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="NO_TLPN">No. Telepon</label>
                            <input type="text" class="form-control" placeholder="Masukkan nomor telepon"
                                name="NO_TLPN" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="SPESIAL_CODE">Kode Spesial</label>
                        <input type="text" class="form-control" placeholder="Masukkan kode spesial"
                            name="SPESIAL_CODE">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Tambah Pengaduan</button>
                </div>
            </form>
        </div>
    </div>
</div>
