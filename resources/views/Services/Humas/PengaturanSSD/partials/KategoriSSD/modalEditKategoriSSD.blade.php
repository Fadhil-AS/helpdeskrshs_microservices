<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Edit Kategori</h6>
            </div>
            <form id="editKategoriForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" class="form-control" id="edit_kategori_nama" name="kategori" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
