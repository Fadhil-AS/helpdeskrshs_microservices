<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Detail Pengaduan</h6>
                        <small class="text-muted">ID: 202405_0000001</small>
                    </div>
                    <div>
                        <small class="text-bold">Status: </small>
                        <span class="badge bg-warning">On Progress</span>
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
                                <strong>Judul Pengaduan</strong><br>Pelayanaan Lambat di Poli Mata
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Pengaduan</strong><br>01 Mei 2024
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Telepon</strong><br>081234567890
                            </div>
                            <div class="col-md-6">
                                <strong>Grading</strong><br><span class="badge bg-warning text-light">Kuning</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Pelapor</strong><br>Ahmad Sulaiman
                            </div>
                            <div class="col-md-6">
                                <strong>Unit Kerja Tujuan</strong><br>Instalasi Rawat Jalan
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Medrec</strong><br>RM123456
                            </div>
                            <div class="col-md-6">
                                <strong>Jenis Laporan</strong><br>Keluhan
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Media Pengaduan</strong><br>Website
                            </div>
                            <div class="col-md-6">
                                <strong>Petugas Pelapor</strong><br>Admin Humas
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Klasifikasi Pengaduan</strong><br>-
                            </div>
                        </div>
                        <div class="mb-2">
                            <strong>Deskripsi Pengaduan</strong>
                            <div class="bg-light p-2 rounded">Pelayanaan di poli mata sangat lambat. Saya sudah
                                menunggu lebih dari 3 jam tetapi belum dipanggil.</div>
                        </div>
                        <div class="mb-4">
                            <strong>Rangkuman Permasalahan</strong>
                            <div class="bg-light p-2 rounded">Waktu tunggu pelayanan yang terlalu lama di Poli Mata.
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
                                <strong>Petugas Evaluasi</strong><br>-
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Evaluasi</strong><br>-
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Penyelesaian Pengaduan</strong><br>Dibina
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Selesai</strong><br>-
                            </div>
                        </div>

                        <!-- Klarifikasi Unit -->
                        <div class="mb-3">
                            <strong>Klarifikasi Unit</strong>
                            <textarea class="form-control bg-light" rows="3"
                                readonly>Isi klarifikasi oleh unit terkait akan ditampilkan di sini.</textarea>
                        </div>

                        <!-- File Bukti Klarifikasi -->
                        <div class="mb-3">
                            <strong>File Bukti Klarifikasi</strong>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                <!-- File preview dummy -->
                                <div class="border rounded p-2" style="width: 120px; text-align: center;">
                                    <img src="path/to/image.jpg" alt="Bukti" class="img-fluid rounded mb-1">
                                    <small class="d-block text-truncate">foto1.jpg</small>
                                </div>
                                <div class="border rounded p-2" style="width: 120px; text-align: center;">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                    <small class="d-block text-truncate">dokumen1.pdf</small>
                                </div>
                                <!-- Tambahkan elemen serupa untuk file lainnya -->
                            </div>
                        </div>

                        <!-- Tindak Lanjut Humas -->
                        <div class="mb-4">
                            <strong>Tindak Lanjut Humas</strong>
                            <textarea class="form-control bg-light" rows="3"
                                readonly>Catatan tindak lanjut oleh tim Humas ditampilkan di sini.</textarea>
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
