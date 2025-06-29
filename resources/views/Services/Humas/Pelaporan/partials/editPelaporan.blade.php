<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="editComplaintForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column">
                            <h6 class="mb-1" id="editModalLabel">Edit Pengaduan</h6>
                            <small class="text-muted" id="editComplaintIdText">ID: -</small>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <small class="text-bold">Status: </small>
                                <span class="badge bg-success" id="editStatusBadge">-</span>
                            </div>
                            {{-- <label for="editStatus" class="form-label fw-bold mb-0 text-nowrap">Status:</label>
                            <select class="form-select form-select-sm" id="editStatus" name="STATUS"
                                style="width: auto;">
                                <option value="Open">Open</option>
                                <option value="On Progress">On Progress</option>
                                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                <option value="Close">Close</option>
                                <option value="Banding">Banding</option>
                            </select> --}}
                        </div>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="tabEditPengaduan" role="tablist">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link active w-100" id="tab1Edit-tab" data-bs-toggle="tab"
                                data-bs-target="#tab1Edit" type="button" role="tab">Informasi Pengaduan</button>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link w-100" id="tab3Edit-tab" data-bs-toggle="tab"
                                data-bs-target="#tab3Edit" type="button" role="tab">Evaluasi &
                                Penyelesaian</button>
                        </li>
                    </ul>


                    <div class="tab-content" id="tabEditContent">
                        <!-- Informasi Pengaduan -->
                        <div class="tab-pane fade show active" id="tab1Edit" role="tabpanel">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editJudul">Judul Pengaduan</label>
                                    <input type="text" class="form-control" value="Pelayanaan Lambat di Poli Mata"
                                        id="editJudul" name="JUDUL_COMPLAINT">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editTanggalPengaduan">Tanggal
                                        Pengaduan</label>
                                    <input type="text" class="form-control " id="editTanggalPengaduan" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editNoTelp">No. Telepon</label>
                                    <input type="text" class="form-control" id="editNoTelp" name="NO_TLPN"
                                        value="081234567890">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold d-block mb-3">Grading</label>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gradingOptions"
                                                id="editGradingHijau" value="Hijau" required>
                                            <label class="form-check-label" for="editGradingHijau">Hijau</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gradingOptions"
                                                id="editGradingKuning" value="Kuning">
                                            <label class="form-check-label" for="editGradingKuning">Kuning</label>
                                        </div>
                                        <div class="form-check form-check-inline me-0">
                                            <input class="form-check-input" type="radio" name="gradingOptions"
                                                id="editGradingMerah" value="Merah">
                                            <label class="form-check-label" for="editGradingMerah">Merah</label>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Pelapor</label>
                                    <input type="text" class="form-control" value="Ahmad Sulaiman"
                                        id="editNamaPelapor" name="NAME">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Unit Kerja Tujuan</label>
                                    <select class="form-select" id="editIdBagian" name="ID_BAGIAN" required>
                                        <option value="" selected disabled>Pilih unit kerja</option>
                                        @if (isset($unitKerja) && $unitKerja->count() > 0)
                                            @foreach ($unitKerja as $uK)
                                                <option value="{{ $uK->ID_BAGIAN }}">{{ $uK->NAMA_BAGIAN }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No. Medrec</label>
                                    <input type="text" class="form-control" value="RM123456" id="editNoMedrec"
                                        name="NO_MEDREC">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editIdJenisLaporan">Jenis Laporan</label>
                                    <select class="form-select" id="editIdJenisLaporan" name="ID_JENIS_LAPORAN"
                                        required>
                                        <option value="" selected disabled>Pilih jenis laporan</option>
                                        @if (isset($JenisLaporan) && $JenisLaporan->count() > 0)
                                            @foreach ($JenisLaporan as $jl)
                                                <option value="{{ $jl->ID_JENIS_LAPORAN }}">{{ $jl->JENIS_LAPORAN }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editIdJenisMedia">Media Pengaduan</label>
                                    <select class="form-select" id="editIdJenisMedia" name="ID_JENIS_MEDIA" required>
                                        <option value="" selected disabled>Pilih media</option>
                                        @if (isset($JenisMedia) && $JenisMedia->count() > 0)
                                            @foreach ($JenisMedia as $jm)
                                                <option value="{{ $jm->ID_JENIS_MEDIA }}">{{ $jm->JENIS_MEDIA }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editPetugasPelapor">Petugas Pelapor</label>
                                    <input type="text" class="form-control" id="editPetugasPelapor"
                                        name="PETUGAS_PELAPOR">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editIdKlasifikasi">Klasifikasi
                                        Pengaduan</label>
                                    <select class="form-select readonly-on-helpdesk" data-live-search="true"
                                        id="editIdKlasifikasi" name="ID_KLASIFIKASI" required>
                                        <option value="" selected disabled>Pilih klasifikasi pengaduan
                                        </option>
                                        @if (isset($klasifikasiPengaduan) && $klasifikasiPengaduan->count() > 0)
                                            @foreach ($klasifikasiPengaduan as $kp)
                                                <option value="{{ $kp->ID_KLASIFIKASI }}">
                                                    {{ $kp->KLASIFIKASI_PENGADUAN }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold" for="editIsiComplaint">Deskripsi Pengaduan</label>
                                <textarea class="form-control" rows="2" id="editIsiComplaint" name="ISI_COMPLAINT" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold" for="editPermasalahan">Rangkuman
                                    Permasalahan</label>
                                <textarea class="form-control" rows="2" id="editPermasalahan" name="PERMASALAHAN"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">File Pengaduan</label>
                                <div class="file-display-container" id="editPengaduanContainer">
                                    <p class="text-muted m-0">Tidak ada file pengaduan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluasi & Penyelesaian -->
                        <div class="tab-pane fade" id="tab3Edit" role="tabpanel">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Petugas Evaluasi</label>
                                    <input type="text" class="form-control " id="editPetugasEvaluasi"
                                        name="PETUGAS_EVALUASI" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal Evaluasi</label>
                                    <input type="text" class="form-control" id="editTanggalEvaluasi" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="editIdPenyelesaian">Penyelesaian
                                        Pengaduan</label>
                                    <select class="form-select" id="editIdPenyelesaian" name="ID_PENYELESAIAN">
                                        <option value="" selected disabled>Pilih penyelesaian</option>
                                        @if (isset($penyelesaianPengaduan) && $penyelesaianPengaduan->count() > 0)
                                            @foreach ($penyelesaianPengaduan as $pp)
                                                <option value="{{ $pp->ID_PENYELESAIAN }}">
                                                    {{ $pp->PENYELESAIAN_PENGADUAN }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tanggal Selesai</label>
                                    <input type="text" class="form-control" id="editTanggalSelesai" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="editKlarifikasiUnitContent">Klarifikasi
                                    Unit</label>
                                <textarea class="form-control" rows="2" id="editKlarifikasiUnitContent" name="KLARIFIKASI_UNIT_TEXT" readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">File Bukti Klarifikasi</label>
                                <div class="file-display-container" id="editBuktiKlarifikasiContainer">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="editTindakLanjutHumasContent">Tindak Lanjut
                                    Humas</label>
                                <textarea class="form-control" rows="2" id="editTindakLanjutHumasContent" name="TINDAK_LANJUT_HUMAS"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="file_tindak_lanjut_input">File Tindak Lanjut
                                    Humas
                                    (Jika Ada)</label>
                                <input type="file"
                                    class="form-control @error('file_tindak_lanjut.*') is-invalid @enderror"
                                    id="FILE_PENGADUAN_input" name="file_tindak_lanjut[]" multiple>
                                @error('file_tindak_lanjut.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">Ukuran file maksimal: 2MB dan file dapat lebih dari
                                    satu.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">Batal</button>
                        <button class="btn btn-simpan" type="submit">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
