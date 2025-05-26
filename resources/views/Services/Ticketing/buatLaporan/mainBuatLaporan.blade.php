@extends('Services.Ticketing.buatLaporan.layouts.headingBuatLaporan')
@section('containBuatLaporan')
    <!-- Hiasan Sudut -->
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-right" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-right" />

    <div class="form-container">
        <!-- Tombol Kembali -->
        <a href="{{ url('/') }}" class="btn btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <h3 class="text-center fw-bold" style="color: #007b8a;">Form Pengaduan</h3>
        <p class="text-center">Silakan isi formulir di bawah ini untuk menyampaikan pengaduan Anda</p>

        <form>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" class="form-control" placeholder="Masukkan nama lengkap anda">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nomor Telepon</label>
                <input type="text" class="form-control" placeholder="Contoh: 08123456789">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nomor Rekam Medis (Opsional)</label>
                <input type="text" class="form-control" placeholder="Masukkan nomor rekam medis jika ada">
                <small class="text-muted">Nomor rekam medis membantu kami mengidentifikasi Anda dengan lebih
                    cepat</small>
            </div>

            <div class="mb-3">
                <input type="file" id="refTicketFile" class="d-none" accept=".jpg, .jpeg, .png, .pdf">
                <label for="refTicketFile" class="btn btn-upload w-30" style="cursor: pointer;">
                    <i class="bi bi-plus-circle"></i> Tambahkan Referensi Tiket Sebelumnya
                </label>
                <div id="refTicketFileInfo" class="mt-2 text-muted small"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                <textarea class="form-control" rows="4" placeholder="Jelaskan secara detail pengaduan anda"></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Bukti Pendukung (Opsional)</label>
                <input type="file" id="buktiPendukungFile" class="d-none" accept=".jpg, .jpeg, .png, .pdf" multiple>
                <label for="buktiPendukungFile" class="upload-box d-block" style="cursor: pointer;">
                    <div class="upload-box-content"> <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0 upload-box-text">Klik untuk upload <span class="fw-light">atau drag and
                                drop</span></p>
                        <small class="text-muted upload-box-hint">Format: JPG, PNG, atau PDF (Maks. 5MB)</small>
                    </div>
                </label>
                <div id="buktiPendukungFileInfo" class="mt-2">
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-simpan">Kirim Laporan</button>
            </div>
        </form>
    </div>
@endsection
