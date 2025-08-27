@extends('Services.Ticketing.lacakTicket.layouts.headingLacakTicketing')
@section('containTicketing')
    <!-- Hiasan Sudut -->
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-right" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-right" />

    <div class="lacak-container">

        <!-- Tombol Kembali -->
        <a href="{{ url('/') }}" class="btn btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <h4 class="fw-bold tiket-title text-center mt-4">Lacak Tiketmu</h4>
        <p class="text-center">Isi kolom di bawah ini dengan Nomor Tiket atau Nama Lengkap Anda (Sesuai KTP).</p>

        <div class="input-group mb-4 mt-5" id="search-container" data-search-url="{{ route('ticketing.lacak.search') }}"
            data-by-name-url="{{ route('ticketing.lacak.by-name') }}">
            <input id="inputTiket" type="text" class="form-control"
                placeholder="Masukkan Nomor Tiket atau Nama Lengkap (Sesuai KTP)" value="{{ $idComplaint ?? '' }}">
            <button class="btn btn-simpan" id="btnCariTiket">
                <i class="bi bi-search"></i> Lacak
            </button>
        </div>

        <!-- Area hasil -->
        <div id="hasilArea">
            <div class="no-data">
                <i class="bi bi-file-earmark-text"></i>
                <div class="text-bold mt-2">Belum ada data</div>
                <div>Masukkan Nomor Tiket atau Nama Lengkap (Sesuai KTP) untuk melihat status laporan Anda.</div>
            </div>
        </div>
        <div id="globalMessages" class="mb-3"></div>

        <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="phoneModalLabel">Verifikasi Nomor Telepon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Untuk menampilkan daftar laporan atas nama <strong
                                id="searchNameDisplay"></strong>, silakan masukkan nomor telepon aktif Anda.</p>

                        <div id="phoneError" class="alert alert-danger d-none mt-2"></div>
                        <div class="input-group mt-2">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="tel" class="form-control" id="inputNomorHp" placeholder="Contoh: 081234567890"
                                required>
                        </div>
                        <small class="form-text text-muted mt-1 d-block">Harus terdiri dari 9-15 digit angka.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-simpan" id="btnVerifyPhone">Verifikasi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ticketListModal" tabindex="-1" aria-labelledby="ticketListModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="ticketListModalLabel">Pilih Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="laporanListContainer" class="list-group mb-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                        <nav aria-label="Page navigation">
                            <ul class="pagination page-tabel mb-0" id="paginationContainer">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="feedbackModalLabel">Terima kasih atas konfirmasi Anda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Mohon berikan saran dan kritik anda untuk membantu kami meningkatkan layanan.</p>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-center d-block">Penilaian (1-5)</label>
                            <div class="d-flex gap-2 justify-content-center">
                                <div id="ratingContainer">
                                    <button type="button" class="rating-btn">1</button>
                                    <button type="button" class="rating-btn">2</button>
                                    <button type="button" class="rating-btn">3</button>
                                    <button type="button" class="rating-btn">4</button>
                                    <button type="button" class="rating-btn">5</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label fw-bold">Saran dan Kritik</label>
                            <textarea class="form-control" id="feedbackText" name="feedback_text" rows="3"
                                placeholder="Bagaimana pengalaman anda dengan layanan kami?"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-simpan" id="btnSubmitFeedback" data-id="">Kirim
                            Feedback</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="belumSelesaiModal" tabindex="-1" aria-labelledby="belumSelesaiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="belumSelesaiModalLabel">Buat Tiket Baru Terkait</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Kami mohon maaf atas ketidaknyamanan Anda. Anda dapat membuat tiket baru yang terkait dengan
                            tiket sebelumnya.</p>
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                Tiket baru akan terhubung dengan tiket <strong
                                    id="refTicketIdWarning">ID_TIKET_LAMA</strong> dan akan mendapatkan prioritas
                                penanganan.
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3 text-muted">
                            <i class="bi bi-link-45deg me-2 fs-4"></i>
                            <span>Tiket Terkait: <strong id="refTicketIdText">ID_TIKET_LAMA</strong></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-simpan" id="btnBuatTiketBaruDariModal" data-id="">Buat
                            Tiket
                            Baru</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('rating-btn')) {
                const clickedButton = event.target;
                const ratingContainer = clickedButton.closest('#ratingContainer');
                if (!ratingContainer) return;

                const allButtons = ratingContainer.querySelectorAll('.rating-btn');

                allButtons.forEach(btn => btn.classList.remove('active'));

                clickedButton.classList.add('active');
            }
        });
    </script>
    <script src="{{ asset('assets/js/Ticketing/lacakTicketing/lacak.js') }}"></script>
@endpush
