<div class="modal fade" id="modalTambahSSD" tabindex="-1" aria-labelledby="modalTambahSSDLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalTambahSSDLabel">Tambah Data SSD Baru</h6>
            </div>
            {{-- Sesuaikan route action dengan route Anda --}}
            <form action="{{ route('humas.pengaturan-ssd.ssd.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea class="form-control" name="pertanyaan" rows="3" placeholder="Masukkan pertanyaan yang sering diajukan"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawaban</label>
                        <textarea class="form-control" name="jawaban" rows="5" placeholder="Masukkan jawaban untuk pertanyaan di atas"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori SSD</label>
                        {{-- <input type="text" class="form-control" name="kategori"
                            placeholder="Contoh: Umum, Pendaftaran, Jadwal Dokter" required> --}}
                        <select class="form-select" name="id_kategori_ssd" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach ($allKategori as $kategori)
                                <option value="{{ $kategori->ID_KATEGORI_SSD }}"
                                    {{ old('id_kategori_ssd') == $kategori->ID_KATEGORI_SSD ? 'selected' : '' }}>
                                    {{ $kategori->NAMA_KATEGORI }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Kategori untuk mengelompokkan pertanyaan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn text-white btn-simpan" type="submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
