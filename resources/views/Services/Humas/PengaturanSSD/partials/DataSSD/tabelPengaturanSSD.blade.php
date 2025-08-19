<div class="container rounded container-tabel my-5 pt-2">
    {{-- Notifikasi akan muncul di sini --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops! Terjadi kesalahan validasi:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="p-4 rounded-top" style="background-color: #00B9AD; color: white;">
        <h5 class="mb-1">Pengaturan Data SSD (Soalan Sering Ditanya)</h5>
        <p class="mb-0">Kelola daftar pertanyaan dan jawaban untuk SSD</p>
    </div>

    <div class="bg-white p-3 rounded-bottom shadow-sm">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <button class="btn btn-tambah-pengaduan text-white btn-teal" data-bs-toggle="modal"
                data-bs-target="#modalTambahSSD">
                <i class="bi bi-plus-circle"></i> Tambah Data SSD
            </button>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Cari pertanyaan...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="border-bottom">
                    <tr class="text-nowrap">
                        <th style="width: 35%;">Pertanyaan</th>
                        <th style="width: 35%;">Jawaban</th>
                        <th style="width: 20%;">Kategori SSD</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data Dummy --}}
                    <tr>
                        <td>Bagaimana cara mendaftar sebagai pasien baru?</td>
                        <td>Pendaftaran pasien baru dapat dilakukan secara online melalui aplikasi...</td>
                        {{-- [MODIFIKASI] Hapus badge, gunakan fw-semibold --}}
                        <td><span class="fw-semibold">Pendaftaran</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD"
                                data-id="1"
                                data-pertanyaan="Bagaimana cara mendaftar sebagai pasien baru?"
                                data-jawaban="Pendaftaran pasien baru dapat dilakukan secara online melalui aplikasi RSHS Mobile atau langsung di loket pendaftaran."
                                data-kategori="Pendaftaran">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal"
                                data-id="1" data-pertanyaan="Bagaimana cara mendaftar sebagai pasien baru?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Di mana lokasi poliklinik jantung?</td>
                        <td>Poliklinik Jantung berada di Gedung Utama lantai 2. Silakan ikuti...</td>
                        <td><span class="fw-semibold">Fasilitas & Layanan</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD"
                                data-id="2"
                                data-pertanyaan="Di mana lokasi poliklinik jantung?"
                                data-jawaban="Poliklinik Jantung berada di Gedung Utama lantai 2. Silakan ikuti petunjuk arah yang tersedia."
                                data-kategori="Lokasi & Arah">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal"
                                data-id="2" data-pertanyaan="Di mana lokasi poliklinik jantung?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Jam berapa waktu besuk pasien?</td>
                        <td>Waktu besuk pasien adalah Pagi: 11:00 - 13:00 WIB dan Sore: 17:00...</td>
                        <td><span class="fw-semibold">Peraturan</span></td>
                        <td>
                           <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD"
                                data-id="3"
                                data-pertanyaan="Jam berapa waktu besuk pasien?"
                                data-jawaban="Waktu besuk pasien adalah Pagi: 11:00 - 13:00 WIB dan Sore: 17:00 - 19:00 WIB."
                                data-kategori="Peraturan">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal"
                                data-id="3" data-pertanyaan="Jam berapa waktu besuk pasien?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{-- Pagination dinonaktifkan untuk data dummy --}}
        </div>
    </div>
</div>
