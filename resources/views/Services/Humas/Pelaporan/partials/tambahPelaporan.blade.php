<div class="modal fade" id="modalTambahPengaduan" tabindex="-1" aria-labelledby="modalTambahPengaduanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPengaduanLabel">Tambah Pengaduan Baru</h5>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('humas.pelaporan-humas.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="JUDUL_COMPLAINT">Judul Pengaduan</label>
                            <input type="text" class="form-control @error('JUDUL_COMPLAINT') is-invalid
                                @enderror" placeholder="Masukkan judul pengaduan" id="JUDUL_COMPLAINT"
                                name="JUDUL_COMPLAINT" required>
                            @error('JUDUL_COMPLAINT')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="NO_TLPN">No. Telepon</label>
                            <input type="text" class="form-control @error('NO_TLPN') is-invalid @enderror"
                                placeholder="Masukkan nomor telepon" id="NO_TLPN" name="NO_TLPN" required>
                            @error('NO_TLPN')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="NAME">Nama Pelapor</label>
                            <input type="text" class="form-control @error('NAME') is-invalid @enderror"
                                placeholder="Masukkan nama pelapor" id="NAME" name="NAME" required>
                            @error('NAME')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="ID_BAGIAN">Unit Kerja Tujuan</label>
                            <select class="form-select @error('ID_BAGIAN') is-invalid
                                @enderror" id="ID_BAGIAN" name="ID_BAGIAN" required>
                                <option selected disabled>Pilih unit kerja</option>
                                @foreach ($unitKerja as $uK)
                                <option value="{{ $uK->ID_BAGIAN }}">{{ $uK->NAMA_BAGIAN }}</option>
                                @endforeach
                            </select>
                            @error('ID_BAGIAN')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="NO_MEDREC">No. Medrec (jika
                                ada)</label>
                            <input type="text" class="form-control @error('NO_MEDREC') is-invalid @enderror"
                                placeholder="Masukkan nomor rekam medis" id="NO_MEDREC" name="NO_MEDREC">
                            @error('NO_MEDREC')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="ID_JENIS_LAPORAN">Jenis
                                Laporan</label>
                            <select class="form-select @error('ID_JENIS_LAPORAN') is-invalid @enderror"
                                id="ID_JENIS_LAPORAN" name="ID_JENIS_LAPORAN" required>
                                <option selected disabled>Pilih jenis laporan</option>
                                @foreach ($JenisLaporan as $jl)
                                <option value="{{ $jl->ID_JENIS_LAPORAN }}">{{ $jl->JENIS_LAPORAN }}</option>
                                @endforeach
                            </select>
                            @error('ID_JENIS_LAPORAN')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="ID_JENIS_MEDIA">Media
                                Pengaduan</label>
                            <select class="form-select @error('ID_JENIS_MEDIA') is-invalid @enderror"
                                id="ID_JENIS_MEDIA" name="ID_JENIS_MEDIA" required>
                                <option selected disabled>Pilih media</option>
                                @foreach ($JenisMedia as $jm)
                                <option value="{{ $jm->ID_JENIS_MEDIA }}">{{ $jm->JENIS_MEDIA }}</option>
                                @endforeach
                            </select>
                            @error('ID_JENIS_MEDIA')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="ID_KLASIFIKASI">Klasifikasi Pengaduan</label>
                            <select class="form-select @error('ID_KLASIFIKASI') is-invalid @enderror"
                                id="ID_KLASIFIKASI" name="ID_KLASIFIKASI" required>
                                <option selected disabled>Pilih klasifikasi pengaduan</option>
                                @foreach ($klasifikasiPengaduan as $kp)
                                <option value="{{ $kp->ID_KLASIFIKASI }}">{{ $kp->KLASIFIKASI_PENGADUAN }}
                                </option>
                                @endforeach
                            </select>
                            @error('ID_KLASIFIKASI')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="ISI_COMPLAINT">Deskripsi Pengaduan</label>
                        <textarea class="form-control @error('ISI_COMPLAINT') is-invalid @enderror" rows="2"
                            placeholder="Masukkan deskripsi pengaduan" id="ISI_COMPLAINT" name="ISI_COMPLAINT"
                            required></textarea>
                        @error('ISI_COMPLAINT')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="PERMASALAHAN">Rangkuman Permasalahan</label>
                        <textarea class="form-control @error('PERMASALAHAN') is-invalid @enderror" rows="2"
                            placeholder="Masukkan rangkuman permasalahan" id="PERMASALAHAN"
                            name="PERMASALAHAN"></textarea>
                        @error('PERMASALAHAN')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="FILE_PENGADUAN_input">File Pengaduan (jika ada)</label>
                        <input type="file" class="form-control @error('FILE_PENGADUAN') is-invalid @enderror"
                            id="FILE_PENGADUAN_input" name="FILE_PENGADUAN">
                        @error('FILE_PENGADUAN')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                <button class="btn text-white btn-simpan" type="submit">Tambah Pengaduan</button>
            </div>
            </form>
        </div>
    </div>
</div>