<div class="modal fade" id="modalEditNomor" tabindex="-1" aria-labelledby="modalEditNomorLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="editNomorForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title" id="modalEditNomorLabel">Ubah Nomor</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_field" name="field_to_update">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Saat Ini:</label>
                        <p><strong id="nomor_lama_text" class=""></strong></p>
                    </div>

                    <div>
                        <label for="edit_nomor_baru" class="form-label fw-bold">Masukkan Nomor Baru:</label>
                        <input type="tel" class="form-control @error('no_tlpn') is-invalid @enderror"
                            id="edit_nomor_baru" name="no_tlpn" value="{{ old('no_tlpn') }}" placeholder="Contoh: 081234567890" required
                            pattern="[0-9]{10,15}" maxlength="15"
                            title="Nomor harus berupa angka dan terdiri dari 10-15 digit angka.">

                        @error('no_tlpn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">Nomor harus berupa angka dan terdiri dari 10-15 digit angka.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
