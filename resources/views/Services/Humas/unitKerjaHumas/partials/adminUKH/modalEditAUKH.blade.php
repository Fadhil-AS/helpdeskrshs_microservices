<div class="modal fade" id="modalEditAdmin" tabindex="-1" aria-labelledby="modalEditAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="formEditAdmin" method="POST" action="">
                @method('PUT')
                @csrf
                <div class="modal-body p-4">
                    <h6 class="mb-1 fw-bold">Edit Admin Unit Kerja</h6>
                    <small class="text-muted mb-3 d-block" id="edit_no_register">No. Register: -</small>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit_username">Username</label>
                        <input type="text" class="form-control @error('USERNAME') is-invalid @enderror"
                            id="edit_username" name="USERNAME" required>
                        @error('USERNAME')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit_name">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_name" name="NAME" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="edit_nip">NIP</label>
                            <input type="text" class="form-control" id="edit_nip" name="NIP" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="edit_no_tlpn">No. Telepon</label>
                            <input type="text" class="form-control" id="edit_no_tlpn" name="NO_TLPN" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="edit_id_bagian">Unit Kerja</label>
                        <select class="form-select" id="edit_id_bagian" name="ID_BAGIAN" required>
                            <option selected disabled>--- Pilih unit kerja ---</option>
                            @foreach ($unitsForDropdown as $unit)
                                <option value="{{ $unit->ID_BAGIAN }}">{{ $unit->ID_BAGIAN }} - {{ $unit->NAMA_BAGIAN }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="edit_validasi">Status Validasi</label>
                        <select class="form-select" id="edit_validasi" name="VALIDASI" required>
                            <option value="Y">Tervalidasi</option>
                            <option value="N">Belum tervalidasi</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-simpan">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
