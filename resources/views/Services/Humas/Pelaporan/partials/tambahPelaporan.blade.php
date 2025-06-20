<div class="modal fade" id="modalTambahPengaduan" tabindex="-1" aria-labelledby="modalTambahPengaduanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPengaduanLabel">Tambah Pengaduan Baru</h5>
            </div>
            <div class="modal-body">
                <form id="formTambahPengaduan" method="POST" action="{{ route('humas.pelaporan-humas.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- Baris 1: Nama Pelapor & Jenis Pelapor --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="jenisPelapor">Jenis Pelapor</label>
                                <select name="jenis_pelapor" id="jenisPelapor" class="form-select" required>
                                    <option value="" selected disabled>Pilih Jenis Pelapor</option>
                                    <option value="Pasien">Pasien</option>
                                    <option value="Non-Pasien">Non-Pasien</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="ID_KLASIFIKASI">Klasifikasi Pengaduan</label>
                                <select class="form-select" id="ID_KLASIFIKASI" name="ID_KLASIFIKASI" required>
                                    <option value="" selected disabled>Pilih klasifikasi</option>
                                    @foreach ($klasifikasiPengaduan as $kp)
                                        <option value="{{ $kp->ID_KLASIFIKASI }}">{{ $kp->KLASIFIKASI_PENGADUAN }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 2: Klasifikasi Pengaduan & No. Telepon --}}
                    <div class="row">
                        <div class="col-md-6">
                             <div class="mb-3">
                                <label class="form-label fw-bold" for="ID_JENIS_MEDIA">Media Pengaduan</label>
                                <select class="form-select" id="ID_JENIS_MEDIA" name="ID_JENIS_MEDIA" required>
                                    <option selected disabled>Pilih media</option>
                                    @foreach ($JenisMedia as $jm)
                                        <option value="{{ $jm->ID_JENIS_MEDIA }}">{{ $jm->JENIS_MEDIA }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="wrapper_nama">
                                <label class="form-label fw-bold" for="NAME">Nama Lengkap</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama lengkap" id="NAME" name="NAME" value="{{ old('NAME') }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 3: No. Medrec & Unit Kerja Tujuan --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3" id="wrapper_no_tlpn">
                                <label class="form-label fw-bold" for="NO_TLPN">Nomor Telepon</label>
                                <input type="text" class="form-control" placeholder="Masukkan nomor telepon" id="NO_TLPN" name="NO_TLPN" value="{{ old('NO_TLPN') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="mb-3" id="wrapper_no_medrec">
                                <label class="form-label fw-bold" for="nomorRekamMedis">Nomor Rekam Medis (Opsional)</label>
                                <input type="text" class="form-control" placeholder="Masukkan nomor rekam medis" id="nomorRekamMedis" name="NO_MEDREC" value="{{ old('NO_MEDREC') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Baris 5: Deskripsi Pengaduan (Panjang Penuh) --}}
                    <div class="mb-3" id="wrapper_deskripsi">
                        <label class="form-label fw-bold" for="ISI_COMPLAINT">Deskripsi Pengaduan</label>
                        <textarea class="form-control" rows="3" placeholder="Masukkan deskripsi pengaduan" id="ISI_COMPLAINT" name="ISI_COMPLAINT" required></textarea>
                    </div>

                    {{-- Baris 7: Upload File (Panjang Penuh) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="FILE_PENGADUAN_input">File Pengaduan (jika ada)</label>
                        <input type="file" class="form-control @error('FILE_PENGADUAN') is-invalid @enderror"
                            id="FILE_PENGADUAN_input" name="FILE_PENGADUAN">
                        @error('FILE_PENGADUAN')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn text-white btn-simpan" form="formTambahPengaduan">Tambah Pengaduan</button>
            </div>
        </div>
    </div>
</div>
