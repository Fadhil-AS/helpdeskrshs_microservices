@extends('Services.Ticketing.buatLaporan.layouts.headingBuatLaporan')
@section('containBuatLaporan')
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-right" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-right" />

    <div class="form-container">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <h3 class="text-center fw-bold" style="color: #007b8a;">Form Pengaduan</h3>
        <p class="text-center">Silakan isi formulir di bawah ini untuk menyampaikan pengaduan Anda.</p>

        {{-- Tempat untuk menampilkan pesan sukses/error dari AJAX --}}
        <div id="formMessage" class="mt-3 mb-3"></div>

        {{-- Menampilkan pesan sukses atau error bawaan Laravel (jika ada redirect manual) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any() && !$request->ajax())
            {{-- Hanya tampilkan jika bukan request AJAX --}}
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formPengaduan" method="POST" action="{{ route('ticketing.store-laporan') }}"
            enctype="multipart/form-data">
            @csrf

            @if (isset($idComplaintReferensi) && !empty($idComplaintReferensi))
                <input type="hidden" name="ID_COMPLAINT_REFERENSI" value="{{ $idComplaintReferensi }}">
                <div class="alert alert-info">
                    Pengaduan ini terkait dengan tiket sebelumnya: <strong>{{ $idComplaintReferensi }}</strong>.
                    Deskripsi yang Anda masukkan di bawah ini juga akan dianggap sebagai feedback Anda terhadap penyelesaian
                    tiket tersebut.
                </div>
            @endif

            <div class="mb-3" id="wrapper_nama">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" class="form-control" placeholder="Masukkan nama lengkap anda" name="NAME" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="ID_KLASIFIKASI">Klasifikasi Pengaduan</label>
                <select name="ID_KLASIFIKASI" id="ID_KLASIFIKASI" class="form-select" required>
                    <option value="">Pilih Klasifikasi Pengaduan</option>
                    @foreach ($klasifikasiPengaduan as $klasifikasi)
                        <option value="{{ $klasifikasi->ID_KLASIFIKASI }}">
                            {{ $klasifikasi->KLASIFIKASI_PENGADUAN }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nomorTelepon" class="form-label fw-bold">Nomor Telepon</label>
                <input type="tel" class="form-control" id="nomorTelepon" name="NO_TLPN"
                    placeholder="Contoh: 08123456789" required maxlength="15" pattern="^08\d{0,13}$" inputmode="numeric"
                    aria-describedby="nomorTeleponHelp nomorTeleponError">
                <div id="nomorTeleponHelp" class="form-text">Nomor telepon harus diawali dengan "08" dan terdiri dari
                    10-15 digit angka.</div>
                <div id="nomorTeleponError" class="invalid-feedback"></div>
            </div>

            <div class="mb-3" id="wrapper_no_medrec">
                <label class="form-label fw-bold">Nomor Rekam Medis (Opsional)</label>
                <input type="text" class="form-control" placeholder="Masukkan nomor rekam medis jika ada"
                    name="NO_MEDREC">
                <small class="text-muted">Nomor rekam medis membantu kami mengidentifikasi Anda dengan lebih cepat.</small>
            </div>

            <div class="mb-3" id="wrapper_deskripsi">
                <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                <textarea class="form-control" rows="4" placeholder="Jelaskan secara detail pengaduan anda" name="PERMASALAHAN"
                    required></textarea>
            </div>

            <div class="mb-4" id="wrapper_bukti">
                <label for="buktiPendukungFile" class="form-label fw-bold">Bukti Pendukung (Opsional)</label>
                <input type="file" id="buktiPendukungFile" name="bukti_pendukung[]" class="d-none"
                    accept=".jpg, .jpeg, .png, .pdf" multiple>

                <label id="buktiPendukungDropZone" for="buktiPendukungFile" class="upload-box d-block"
                    style="cursor: pointer;">
                    <div class="upload-box-content">
                    </div>
                </label>
                <div id="buktiPendukungFileErrors" class="text-danger mt-2"></div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-simpan">Kirim Laporan</button>
            </div>
        </form>
    </div>
@endsection
