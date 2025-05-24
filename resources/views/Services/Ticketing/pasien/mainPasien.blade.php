@extends('Services.Ticketing.pasien.layouts.headingPasien')
@section('containPasien')
    <!-- Hiasan Sudut -->
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-right" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-right" />

    <!-- Header -->
    <div class="text-center judul mb-5">
        <h3 class="fw-bold" style="color: #267B84;">Layanan Pengaduan Pasien RSHS Bandung</h3>
        <p style="margin-top: 2vh;">Kami berkomitmen untuk memberikan pelayanan terbaik. Sampaikan laporan Anda untuk
            membantu
            kami meningkatkan kualitas layanan.</p>
    </div>

    <!-- 3 Cards -->
    <div class="container">
        <div class="row g-4 justify-content-center">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center mt-3">
                        <div class="circle-icon" style="background-color: #00B9AD;">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <h5 class="fw-bold pt-3 text-1">Laporkan Kendala</h5>
                        <small class="text-muted text-2">Sampaikan keluhan atau saran Anda</small>
                        <p class="mt-3 text-3">Buat laporan baru untuk menyampaikan kendala, keluhan, atau saran Anda
                            terkait
                            layanan RSHS Bandung.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 position-absolute bottom-0 start-0 w-100 p-4">
                        <a href="{{ url('/ticketing/pengaduan') }}" class="btn text-light w-100"
                            style="background-color: #00B9AD;">Buat
                            Laporan <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center mt-3">
                        <div class="circle-icon" style="background-color: #60C0D0;">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <h5 class="fw-bold pt-3 text-1">Lacak Tiket</h5>
                        <small class="text-muted text-2">Periksa status laporan Anda</small>
                        <p class="mt-3 text-3">Masukkan nomor tiket untuk melacak status laporan Anda dan melihat
                            perkembangan
                            penanganan.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 position-absolute bottom-0 start-0 w-100 p-4">
                        <a href="{{ url('/ticketing/lacak-ticketing') }}" class="btn text-light w-100"
                            style="background-color: #60C0D0;">Lacak
                            Tiket <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center mt-3">
                        <div class="circle-icon" style="background-color: #CDDC29;">
                            <i class="bi bi-question-circle-fill"></i>
                        </div>
                        <h5 class="fw-bold pt-3 text-1">Soalan Sering Ditanya</h5>
                        <small class="text-muted text-2">Temukan jawaban untuk pertanyaan umum</small>
                        <p class="mt-3 text-3">Lihat jawaban untuk pertanyaan yang sering diajukan dan informasi bantuan
                            lainnya.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 position-absolute bottom-0 start-0 w-100 p-4">
                        <a href="{{ url('/ssd') }}" class="btn text-light w-100" style="background-color: #CDDC29;">Lihat
                            SSD <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
