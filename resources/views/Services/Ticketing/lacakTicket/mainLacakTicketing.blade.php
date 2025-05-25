@extends('Services.Ticketing.lacakTicket.layouts.headingLacakTicketing')
@section('containTicketing')
    <!-- Hiasan Sudut -->
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-right" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-right" />

    <div class="lacak-container">

        <!-- Tombol Kembali -->
        <a href="{{ url('/ticketing') }}" class="btn btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <h4 class="fw-bold tiket-title text-center mt-4">Lacak Tiketmu</h4>
        <p class="text-center">Isikan kolom input dibawah ini bisa menggunakan no tiket, no telepon, nama, atau no
            medrec.</p>

        <div class="input-group mb-4 mt-5">
            <input id="inputTiket" type="text" class="form-control"
                placeholder="Masukkan no tiket/no telepon/nama/no medrec">
            <button class="btn btn-simpan text-white" onclick="cariTiket()">
                <i class="bi bi-search"></i> Lacak
            </button>
        </div>

        <!-- Area hasil -->
        <div id="hasilArea">
            <div class="no-data">
                <i class="bi bi-file-earmark-text"></i>
                <div class="text-bold mt-2">Belum ada data</div>
                <div>Masukkan no tiket/no telepon/nama/no medrec untuk melihat status laporan Anda</div>
            </div>
        </div>
    </div>
@endsection
