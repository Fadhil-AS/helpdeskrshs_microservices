<div class="modal fade" id="modalEditSSD" tabindex="-1" aria-labelledby="modalEditSSDLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {{-- Action akan diisi oleh JavaScript --}}
            <form id="editSSDForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title" id="modalEditSSDLabel">Edit Data SSD</h6>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea class="form-control" id="edit_pertanyaan" name="pertanyaan" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawaban</label>
                        <textarea class="form-control" id="edit_jawaban" name="jawaban" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori SSD</label>
                        <input type="text" class="form-control" id="edit_kategori" name="kategori" required>
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
