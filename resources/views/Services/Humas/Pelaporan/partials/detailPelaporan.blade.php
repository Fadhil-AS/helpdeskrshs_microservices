<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Detail Pengaduan</h6>
                        <small class="text-muted" id="detailIdComplaint">ID: -</small>
                    </div>
                    <div>
                        <small class="text-bold">Status: </small>
                        <span class="badge bg-success" id="detailStatus">-</span>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="tabDetailPengaduan" role="tablist">
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link active w-100" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1"
                            type="button" role="tab">Informasi Pengaduan</button>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link w-100" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3"
                            type="button" role="tab">Evaluasi & Penyelesaian</button>
                    </li>
                </ul>

                <div class="tab-content" id="tabContent">
                    <!-- Informasi Pengaduan -->
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Judul Pengaduan</strong><br><span id="detailJudul">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Pengaduan</strong><br><span id="detailTanggalPengaduan">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Telepon</strong><br><span id="detailNoTelp">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Klarifikasi</strong><br><span class="badge"
                                    id="detailKlarifikasiStatus">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Pelapor</strong><br><span id="detailNamaPelapor">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Grading</strong><br><span class="badge bg-warning text-light"
                                    id="detailGrading">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Medrec</strong><br><span id="detailNoMedrec">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Unit Kerja Tujuan</strong><br><span id="detailUnitKerja">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Media Pengaduan</strong><br><span id="detailMediaPengaduan">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Jenis Laporan</strong><br><span id="detailJenisLaporan">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Klasifikasi Pengaduan</strong><br><span id="detailKlasifikasiPengaduan">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Petugas Pelapor</strong><br><span id="detailPetugasPelapor">-</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold pb-2">Deskripsi Pengaduan</label>
                            <textarea class="form-control bg-light" rows="2" id="detailDeskripsiPengaduanContent" readonly>-</textarea>
                            {{-- <strong>Deskripsi Pengaduan</strong>
                            <div class="bg-light p-2 rounded" id="detailDeskripsiPengaduanContent">-</div> --}}
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold pb-2">Rangkuman Permasalahan</label>
                            <textarea class="form-control bg-light" rows="2" id="detailRangkumanPermasalahanContent" readonly>-</textarea>
                            {{-- <strong>Rangkuman Permasalahan</strong>
                            <div class="bg-light p-2 rounded" id="detailRangkumanPermasalahanContent">-
                            </div> --}}
                        </div>
                        <div class="mb-4">
                            <label class="fw-bold pb-2">File Pengaduan</label>
                            <div class="file-display-container" id="filePengaduanContainer">
                                <p class="text-muted m-0">Tidak ada file pengaduan.</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                            <!-- <button class="btn btn-edit">Edit</button> -->
                        </div>
                    </div>

                    <!-- Evaluasi & Penyelesaian -->
                    <div class="tab-pane fade" id="tab3" role="tabpanel">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Petugas Evaluasi</strong><br><span id="detailPetugasEvaluasi">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Evaluasi</strong><br><span id="detailTanggalEvaluasi">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Penyelesaian Pengaduan</strong><br><span
                                    id="detailPenyelesaianPengaduan">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Selesai</strong><br><span id="detailTanggalSelesai">-</span>
                            </div>
                        </div>

                        <!-- Klarifikasi Unit -->
                        <div class="mb-3">
                            <label class="fw-bold pb-2">Klarifikasi Unit</label>
                            <textarea class="form-control bg-light" rows="2" id="detailKlarifikasiUnitContent" readonly>-</textarea>
                        </div>

                        <!-- File Bukti Klarifikasi -->
                        <div class="mb-3">
                            <label class="fw-bold pb-2">File Bukti Klarifikasi</label>
                            <div class="file-display-container" id="buktiKlarifikasiContainer">
                                {{-- <div class="file-klarifikasi-item"> <a href="images\logoRSHS.png" target=""
                                        rel="noopener noreferrer" title="foto1.jpg">
                                        <img src="path/to/image.jpg" alt="Bukti Foto" class="img-fluid rounded mb-1">
                                        <small class="d-block text-truncate">foto1.jpg</small>
                                    </a>
                                </div>
                                <div class="file-klarifikasi-item"> <a href="path/to/document.pdf" target=""
                                        rel="noopener noreferrer" class="text-decoration-none text-dark"
                                        title="dokumen1.pdf">
                                        <i class="bi bi-file-earmark-pdf display-4 text-danger mb-1"></i> <small
                                            class="d-block text-truncate">dokumen1.pdf</small>
                                    </a>
                                </div> --}}
                                <p class="text-muted">Tidak ada file bukti klarifikasi.</p>
                            </div>
                        </div>

                        <!-- Tindak Lanjut Humas -->
                        <div class="mb-4">
                            <label class="fw-bold pb-2">Tindak Lanjut Humas</label>
                            <textarea class="form-control bg-light" rows="2" id="detailTindakLanjutHumasContent" readonly>Catatan tindak lanjut oleh tim Humas ditampilkan di sini.</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold pb-2">File Tindak Lanjut Humas</label>
                            <div class="file-display-container" id="fileTindakLanjutContainer">
                                <p class="text-muted m-0">Tidak ada file tindak lanjut humas.</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                            <!-- <button class="btn btn-edit">Edit</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
