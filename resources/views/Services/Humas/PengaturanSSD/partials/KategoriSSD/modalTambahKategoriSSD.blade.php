<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Kategori Baru</h6>
            </div>
            <form action="{{ route('humas.pengaturan-ssd.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" class="form-control" name="nama_kategori" placeholder="Masukkan nama kategori"
                        required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
