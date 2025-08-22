<div class="container rounded container-tabel my-5 pt-2">
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
                <input type="text" class="form-control border-start-0" placeholder="Cari pertanyaan..."
                    id="searchSsdInput" name="search_ssd">
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
                <tbody id="ssdTableBody">
                    {{-- @forelse ($ssds as $ssd)
                        <tr>
                            <td>{{ $ssd->PERTANYAAN_SSD }}</td>
                            <td>{{ $ssd->JAWABAN_SSD }}</td>
                            <td>
                                <span class="fw-semibold">{{ $ssd->kategori->NAMA_KATEGORI ?? 'Tanpa Kategori' }}</span>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD"
                                    data-id="{{ $ssd->ID_SSD }}" data-pertanyaan="{{ $ssd->PERTANYAAN_SSD }}"
                                    data-jawaban="{{ $ssd->JAWABAN_SSD }}"
                                    data-kategori-id="{{ $ssd->ID_KATEGORI_SSD }}">
                                    <i class="bi bi-pencil-square me-2"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal"
                                    data-id="{{ $ssd->ID_SSD }}" data-pertanyaan="{{ $ssd->PERTANYAAN_SSD }}">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data SSD.</td>
                        </tr>
                    @endforelse --}}
                    @include('Services.Humas.PengaturanSSD.partials.DataSSD.tabelBodyPengaturanSSD')
                    {{-- <tr>
                        <td>Bagaimana cara mendaftar sebagai pasien baru?</td>
                        <td>Pendaftaran pasien baru dapat dilakukan secara online melalui aplikasi...</td>
                        <td><span class="fw-semibold">Pendaftaran</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD" data-id="1"
                                data-pertanyaan="Bagaimana cara mendaftar sebagai pasien baru?"
                                data-jawaban="Pendaftaran pasien baru dapat dilakukan secara online melalui aplikasi RSHS Mobile atau langsung di loket pendaftaran."
                                data-kategori="Pendaftaran">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal" data-id="1"
                                data-pertanyaan="Bagaimana cara mendaftar sebagai pasien baru?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Di mana lokasi poliklinik jantung?</td>
                        <td>Poliklinik Jantung berada di Gedung Utama lantai 2. Silakan ikuti...</td>
                        <td><span class="fw-semibold">Fasilitas & Layanan</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD" data-id="2"
                                data-pertanyaan="Di mana lokasi poliklinik jantung?"
                                data-jawaban="Poliklinik Jantung berada di Gedung Utama lantai 2. Silakan ikuti petunjuk arah yang tersedia."
                                data-kategori="Lokasi & Arah">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal" data-id="2"
                                data-pertanyaan="Di mana lokasi poliklinik jantung?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Jam berapa waktu besuk pasien?</td>
                        <td>Waktu besuk pasien adalah Pagi: 11:00 - 13:00 WIB dan Sore: 17:00...</td>
                        <td><span class="fw-semibold">Peraturan</span></td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditSSD" data-id="3"
                                data-pertanyaan="Jam berapa waktu besuk pasien?"
                                data-jawaban="Waktu besuk pasien adalah Pagi: 11:00 - 13:00 WIB dan Sore: 17:00 - 19:00 WIB."
                                data-kategori="Peraturan">
                                <i class="bi bi-pencil-square me-2"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#hapusModal" data-id="3"
                                data-pertanyaan="Jam berapa waktu besuk pasien?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr> --}}
                </tbody>
            </table>

        </div>

        <div class="d-flex justify-content-end mt-3" id="ssdPagination">
            {{ $ssds->links() }}
        </div>
    </div>
</div>
