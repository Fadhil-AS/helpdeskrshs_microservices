<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true"
    data-url-template="{{ route('unitKerja.dashboard.show', ['id_complaint' => 'PLACEHOLDER']) }}">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Detail Pengaduan</h6>
                        <small class="text-muted">ID: <span id="detail-id"></span></small>
                    </div>
                    <div>
                        <small class="text-bold">Status: </small>
                        <span id="detail-status-badge"></span>
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
                                <strong>Judul Pengaduan</strong><br><span id="detail-judul"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Pengaduan</strong><br><span id="detail-tanggal-pengaduan"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Telepon</strong><br><span id="detail-no-tlpn"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Grading</strong><br><span id="detail-grading-badge"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Pelapor</strong><br><span id="detail-nama-pelapor"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Unit Kerja Tujuan</strong><br><span id="detail-unit-kerja"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Medrec</strong><br><span id="detail-no-medrec"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Jenis Laporan</strong><br><span id="detail-jenis-laporan"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Media Pengaduan</strong><br><span id="detail-media-pengaduan"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Petugas Pelapor</strong><br><span id="detail-petugas-pelapor"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Klasifikasi Pengaduan</strong><br><span id="detail-klasifikasi"></span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold pb-2">Deskripsi Pengaduan</label>
                            <textarea class="form-control bg-light" rows="2" id="detail-deskripsi" readonly></textarea>
                            {{-- <strong>Deskripsi Pengaduan</strong>
                            <div class="bg-light p-2 rounded" id="detail-deskripsi"></div> --}}
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold pb-2">Rangkuman Permasalahan</label>
                            <textarea class="form-control bg-light" rows="2" id="detail-permasalahan" readonly></textarea>
                            {{-- <strong>Rangkuman Permasalahan</strong>
                            <div class="bg-light p-2 rounded" id="detail-permasalahan">
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
                            <button class="btn btn-edit">Edit</button>
                        </div>
                    </div>

                    <!-- Evaluasi & Penyelesaian -->
                    <div class="tab-pane fade" id="tab3" role="tabpanel">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Petugas Evaluasi</strong><br><span id="detail-petugas-evaluasi">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Evaluasi</strong><br><span id="detail-tanggal-evaluasi"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Penyelesaian Pengaduan</strong><br><span id="detail-penyelesaian"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Selesai</strong><br><span id="detail-tanggal-selesai"></span>
                            </div>
                        </div>

                        <!-- Klarifikasi Unit -->
                        <div class="mb-3">
                            <label class="fw-bold pb-2">Klarifikasi Unit</label>
                            <textarea class="form-control bg-light" rows="2" id="detail-klarifikasi-unit" readonly></textarea>
                        </div>

                        <!-- File Bukti Klarifikasi -->
                        <div class="mb-3">
                            <label class="fw-bold pb-2">File Bukti Klarifikasi</label>
                            <div class="file-display-container" id="detail-file-list">
                                <!-- File preview dummy -->
                                {{-- <div class="border rounded p-2" style="width: 120px; text-align: center;">
                                    <img src="path/to/image.jpg" alt="Bukti" class="img-fluid rounded mb-1">
                                    <small class="d-block text-truncate">foto1.jpg</small>
                                </div>
                                <div class="border rounded p-2" style="width: 120px; text-align: center;">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                    <small class="d-block text-truncate">dokumen1.pdf</small>
                                </div> --}}
                                <!-- Tambahkan elemen serupa untuk file lainnya -->
                            </div>
                        </div>

                        <!-- Tindak Lanjut Humas -->
                        <div class="mb-4">
                            <label class="fw-bold pb-2">Tindak Lanjut Humas</label>
                            <textarea class="form-control bg-light" rows="2" id="detail-tindak-lanjut-humas" readonly></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold pb-2">File Tindak Lanjut Humas</label>
                            <div class="file-display-container" id="fileTindakLanjutContainer">
                                <p class="text-muted m-0">Tidak ada file tindak lanjut humas.</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-edit">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
