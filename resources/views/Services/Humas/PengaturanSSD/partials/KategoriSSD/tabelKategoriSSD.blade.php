<div class="container rounded container-tabel mt-5 pt-2">
    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Manajemen Kategori SSD</h5>
        <p class="mb-0">Kelola daftar kategori untuk pertanyaan SSD</p>
    </div>

    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <button class="btn btn-tambah-pengaduan text-white" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </button>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari kategori...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th style="width: 90%;">Kategori SSD</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data Dummy untuk Kategori --}}
                    <tr>
                        <td><span class="fw-semibold">Pendaftaran</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditKategori" data-id="1" data-kategori="Pendaftaran">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusKategoriModal" data-id="1" data-kategori="Pendaftaran">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="fw-semibold">Fasilitas & Layanan</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditKategori" data-id="2" data-kategori="Lokasi & Arah">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusKategoriModal" data-id="2" data-kategori="Lokasi & Arah">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="fw-semibold">Peraturan</span></td>
                        <td>
                           <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditKategori" data-id="3" data-kategori="Peraturan">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusKategoriModal" data-id="3" data-kategori="Peraturan">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
