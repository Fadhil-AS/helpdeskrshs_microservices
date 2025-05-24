    <script>
        function cariTiket() {
            const input = document.getElementById('inputTiket').value.trim();
            const hasil = document.getElementById('hasilArea');

            if (input === '12345678') {
                hasil.innerHTML = `
                <div class="container border rounded p-4 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Tiket: 12345678</strong>
                        <span class="status-badge">On Progress</span>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="info-label">Tanggal Dibuat</div>
                            <div class="fw-bold">4/5/2025</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Tanggal Diperbarui</div>
                            <div class="fw-bold">-</div>
                        </div>
                    </div>

                    <hr>
                    <div class="info-label">Ditangani Oleh</div>
                    <div class="fw-bold mb-3">Bagian Umum</div>

                    <hr>
                    <div class="info-label">Status Terkini</div>
                    <div class="mb-3">Laporan Anda sedang dalam proses peninjauan oleh tim terkait.</div>

                    <hr>
                    <h5 class="fw-bold">Riwayat penanganan</h5>
                    <p>Perkembangan penanganan tiket Anda</p>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="fw-bold">Pasien <span class="text-muted small fw-normal">10 Mei 2025 pukul 08:00</span></div>
                            <div class="timeline-title">Tiket Dibuat</div>
                            <div>Tiket 12345678 telah dibuat</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Humas <span class="text-muted small fw-normal">10 Mei 2025 pukul 08:30</span></div>
                            <div class="timeline-title">Tiket diterima oleh humas</div>
                            <div>Tiket telah diterima dan sedang diproses</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Humas <span class="text-muted small fw-normal">10 Mei 2025 pukul 08:50</span></div>
                            <div class="timeline-title">Tiket diteruskan ke Tim Unit Kerja</div>
                            <div>Tiket diteruskan ke unit kerja terkait untuk penanganan lebih lanjut</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Unit Kerja Bagian Umum <span class="text-muted small fw-normal">10 Mei 2025 pukul 10:15</span></div>
                            <div class="timeline-title">Penanganan dalam proses</div>
                            <div>Sedang dilakukan pengecekan terkait pengaduan yang diajukan. Estimasi penyelesaian 1 jam.</div>
                        </div>
                    </div>
                </div>
                `;
            } else if (input === '23456789') {
                hasil.innerHTML = `
                <div class="container border rounded p-4 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Tiket: 23456789</strong>
                        <span class="status-badge bg-warning text-light">Menunggu Konfirmasi</span>
                    </div>

                    <!-- Konfirmasi Container -->
                    <div class="alert alert-warning mt-4 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-clock me-2 fs-4"></i>
                            <strong class="me-auto">Waktu Konfirmasi Tersisa</strong>
                        </div>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" style="width: 80%;"></div>
                        </div>
                        <p>Anda memiliki waktu <strong>51:52:55</strong> untuk mengkonfirmasi penyelesaian tiket ini. Jika tidak ada konfirmasi, tiket akan otomatis ditutup.</p>
                        <div class="d-flex gap-2">
                            <!-- Tombol -->
                            <button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                            <i class="bi bi-hand-thumbs-up pe-1"></i> Masalah Selesai
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content p-3">
                                <div class="modal-body">
                                    <h5 class="fw-bold">Terima kasih atas konfirmasi Anda</h5>
                                    <p>Mohon berikan feedback anda untuk membantu kami meningkatkan layanan.</p>

                                    <div class="mb-3">
                                    <label class="form-label fw-bold">Penilaian (1-5)</label>
                                    <div class="d-flex gap-2">
                                        <div id="ratingContainer">
                                            <button class="rating-btn">1</button>
                                            <button class="rating-btn">2</button>
                                            <button class="rating-btn">3</button>
                                            <button class="rating-btn">4</button>
                                            <button class="rating-btn">5</button>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="mb-3">
                                    <label class="form-label fw-bold">Feedback</label>
                                    <textarea class="form-control" rows="3" placeholder="Bagaimana pengalaman anda dengan layanan kami?"></textarea>
                                    </div>

                                    <div class="text-end">
                                    <button type="button" class="btn btn-simpan">Kirim Feedback</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>

                            <!-- Tombol -->
                            <button class="btn btn-danger btn-md" data-bs-toggle="modal" data-bs-target="#belumSelesaiModal">
                            <i class="bi bi-hand-thumbs-up pe-1"></i> Masalah Belum Selesai
                            </button>

                            <!-- Modal Buat Tiket Baru Terkait -->
                            <div class="modal fade" id="belumSelesaiModal" tabindex="-1" aria-labelledby="belumSelesaiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content p-3">
                                    <div class="modal-body">
                                        <h5 class="fw-bold mb-2">Buat Tiket Baru Terkait</h5>
                                        <p>Kami mohon maaf atas ketidaknyamanan Anda. Anda dapat membuat tiket baru yang terkait dengan tiket sebelumnya</p>

                                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <div>
                                            Tiket baru akan terhubung dengan tiket <strong>12345678</strong> dan akan mendapatkan prioritas penanganan.
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-3 text-muted">
                                            <i class="bi bi-link-45deg me-2"></i>
                                            <span>Tiket Terkait: <strong>12345678</strong></span>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-outline-danger me-2" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-simpan">Buat Tiket Baru</button>
                                        </div>
                                        </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="info-label">Tanggal Dibuat</div>
                            <div class="fw-bold">4/5/2025</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Tanggal Diperbarui</div>
                            <div class="fw-bold">-</div>
                        </div>
                    </div>

                    <hr>
                    <div class="info-label">Ditangani Oleh</div>
                    <div class="fw-bold mb-3">Bagian Umum</div>

                    <hr>
                    <div class="info-label">Status Terkini</div>
                    <div class="mb-3">Laporan Anda sedang dalam proses peninjauan oleh tim terkait.</div>

                    <hr>
                    <h5 class="fw-bold">Riwayat penanganan</h5>
                    <p>Perkembangan penanganan tiket Anda</p>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="fw-bold">Pasien <span class="text-muted small fw-normal">10 Mei 2025 pukul 08:00</span></div>
                            <div class="timeline-title">Tiket Dibuat</div>
                            <div>Tiket 23456789 telah dibuat</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Humas <span class="text-muted small fw-normal">10 Mei 2025 pukul 08:30</span></div>
                            <div class="timeline-title">Tiket diterima oleh humas</div>
                            <div>Tiket telah diterima dan sedang diproses</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Humas <span class="text-muted small fw-normal">10 Mei 2025 pukul 08:50</span></div>
                            <div class="timeline-title">Tiket diteruskan ke Tim Unit Kerja</div>
                            <div>Tiket diteruskan ke unit kerja terkait untuk penanganan lebih lanjut</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Unit Kerja Bagian Umum <span class="text-muted small fw-normal">10 Mei 2025 pukul 10:15</span></div>
                            <div class="timeline-title">Penanganan dalam proses</div>
                            <div>Sedang dilakukan pengecekan terkait pengaduan yang diajukan. Estimasi penyelesaian 1 jam.</div>
                        </div>
                        <div class="timeline-item">
                            <div class="fw-bold">Unit Kerja Bagian Umum <span class="text-muted small fw-normal">11 Mei 2025 pukul 09:30</span></div>
                            <div class="timeline-title">Masalah telah diselesaikan</div>
                            <div>Tiket yang diajukan telah diselesaikan dan diberikan solusi. Menunggu konfirmasi penyelesaian</div>
                        </div>
                    </div>
                </div>
                `;
            } else {
                hasil.innerHTML = `
                <div class="no-data">
                    <i class="bi bi-file-earmark-text"></i>
                    <div class="text-bold mt-2">Belum ada data</div>
                    <div>Masukkan no tiket/no telepon/nama/no medrec untuk melihat status laporan Anda</div>
                </div>
                `;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
