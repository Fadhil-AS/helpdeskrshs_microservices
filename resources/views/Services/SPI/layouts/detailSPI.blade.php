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
                                <strong>Unit Kerja Tujuan</strong><br><span id="detailUnitKerja">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Pelapor</strong><br><span id="detailNamaPelapor">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Jenis Laporan</strong><br><span id="detailJenisLaporan">-</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <strong>No. Medrec</strong><br><span id="detailNoMedrec">-</span>
                            </div>


                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <strong>Media Pengaduan</strong><br><span id="detailMediaPengaduan">-</span>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <strong>Klasifikasi Pengaduan</strong><br><span id="detailKlasifikasiPengaduan">-</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold pb-2">Deskripsi Pengaduan</label>
                            <textarea class="form-control bg-light" rows="2" id="detailDeskripsiPengaduanContent" readonly>-</textarea>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold pb-2">Rangkuman Permasalahan</label>
                            <textarea class="form-control bg-light" rows="2" id="detailRangkumanPermasalahanContent" readonly>-</textarea>
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
                </div>
            </div>
        </div>
    </div>
</div>
