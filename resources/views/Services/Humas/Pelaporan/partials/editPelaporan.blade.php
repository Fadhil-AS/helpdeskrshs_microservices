<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Edit Pengaduan</h6>
                        <small class="text-muted">ID: 202405_0000001</small>
                    </div>
                    <!-- <div class="d-flex align-items-center gap-2">
                            <div>
                                <label class="form-label fw-bold mb-0">Status</label>
                                <select class="form-select form-select-sm">
                                    <option>Open</option>
                                    <option>On Progress</option>
                                    <option>Close</option>
                                </select>
                            </div>
                        </div> -->
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="tabEditPengaduan" role="tablist">
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link active w-100" id="tab1Edit-tab" data-bs-toggle="tab"
                            data-bs-target="#tab1Edit" type="button" role="tab">Informasi Pengaduan</button>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link w-100" id="tab3Edit-tab" data-bs-toggle="tab" data-bs-target="#tab3Edit"
                            type="button" role="tab">Evaluasi &
                            Penyelesaian</button>
                    </li>
                </ul>

                <div class="tab-content" id="tabEditContent">
                    <!-- Informasi Pengaduan -->
                    <div class="tab-pane fade show active" id="tab1Edit" role="tabpanel">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Judul Pengaduan</label>
                                <input type="text" class="form-control" value="Pelayanaan Lambat di Poli Mata">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Pengaduan</label>
                                <input type="text" class="form-control bg-light" value="01 Mei 2024" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. Telepon</label>
                                <input type="text" class="form-control" value="081234567890">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold d-block mb-3">Grading</label>
                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gradingOptions"
                                            id="gradingHijau" value="hijau" checked> <label class="form-check-label"
                                            for="gradingHijau">Hijau</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gradingOptions"
                                            id="gradingKuning" value="kuning">
                                        <label class="form-check-label" for="gradingKuning">Kuning</label>
                                    </div>

                                    <div class="form-check form-check-inline me-0"> <input class="form-check-input"
                                            type="radio" name="gradingOptions" id="gradingMerah" value="merah">
                                        <label class="form-check-label" for="gradingMerah">Merah</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Pelapor</label>
                                <input type="text" class="form-control" value="Ahmad Sulaiman">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Unit Kerja Tujuan</label>
                                <select class="form-select">
                                    <option selected>Instalasi Rawat Jalan</option>
                                    <option>Unit Kerja Dummy 1</option>
                                    <option>Unit Kerja Dummy 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. Medrec</label>
                                <input type="text" class="form-control" value="RM123456">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Laporan</label>
                                <select class="form-select">
                                    <option selected>Keluhan</option>
                                    <option>Saran</option>
                                    <option>Kritik</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Media Pengaduan</label>
                                <select class="form-select">
                                    <option selected>Website</option>
                                    <option>Email</option>
                                    <option>Telepon</option>
                                    <option>SMS</option>
                                    <option>WhatsApp</option>
                                    <option>Instagram</option>
                                    <option>Facebook</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Petugas Pelapor</label>
                                <input type="text" class="form-control" value="Admin Humas">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Klasifikasi Pengaduan</label>
                            <input type="text" class="form-control" value="">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                            <textarea class="form-control" rows="3">Pelayanaan di poli mata sangat lambat. Saya sudah menunggu lebih dari 3 jam tetapi belum dipanggil.</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Rangkuman Permasalahan</label>
                            <textarea class="form-control" rows="3">Waktu tunggu pelayanan yang terlalu lama di Poli Mata.</textarea>
                        </div>
                    </div>

                    <!-- Evaluasi & Penyelesaian -->
                    <div class="tab-pane fade" id="tab3Edit" role="tabpanel">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Petugas Evaluasi</label>
                                <input type="text" class="form-control bg-light" value="-" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Evaluasi</label>
                                <input type="text" class="form-control bg-light" value="-" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Penyelesaian Pengaduan</label>
                                <select class="form-select">
                                    <option selected>Dibina</option>
                                    <option>Diberi Sanksi</option>
                                    <option>Tidak Lanjut</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <input type="text" class="form-control bg-light" value="-" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Klarifikasi Unit</label>
                            <textarea class="form-control bg-light" rows="3" readonly>Isi klarifikasi oleh unit terkait akan ditampilkan di sini.</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File Bukti Klarifikasi</label>
                            <div class="d-flex flex-wrap gap-3 mt-2 bg-light" id="buktiKlarifikasiContainer">
                                <div class="file-klarifikasi-item"> <a href="images\logoRSHS.png" target=""
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
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tindak Lanjut Humas</label>
                            <textarea class="form-control" rows="3">Catatan tindak lanjut oleh tim Humas ditampilkan di sini.</textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-simpan">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</div>
