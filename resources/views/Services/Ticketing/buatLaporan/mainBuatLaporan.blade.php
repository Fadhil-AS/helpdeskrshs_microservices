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

        <div id="formMessage" class="mt-3 mb-3"></div>

        <form id="formPengaduan" method="POST" action="{{ route('ticketing.store-laporan') }}"
            data-upload-url="{{ route('ticketing.upload-file') }}" data-csrf-token="{{ csrf_token() }}"
            data-redirect-url="{{ url('/') }}" enctype="multipart/form-data" novalidate>
            @csrf

            @if (isset($idComplaintReferensi) && !empty($idComplaintReferensi))
                <input type="hidden" name="ID_COMPLAINT_REFERENSI" value="{{ $idComplaintReferensi }}">
                <div class="alert alert-info">
                    Pengaduan ini terkait dengan tiket sebelumnya: <strong>{{ $idComplaintReferensi }}</strong>.
                    Deskripsi yang Anda masukkan di bawah ini juga akan dianggap sebagai feedback Anda terhadap penyelesaian
                    tiket tersebut.
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label fw-bold" for="jenisPelapor">Jenis Pelapor</label>
                <select name="jenis_pelapor" id="jenisPelapor" class="form-select" required>
                    <option value="" selected disabled>Pilih Jenis Pelapor</option>
                    <option value="Pasien">Pasien</option>
                    <option value="Non-Pasien">Non-Pasien</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="ID_KLASIFIKASI">Klasifikasi Pengaduan</label>
                <select name="ID_KLASIFIKASI" id="ID_KLASIFIKASI" class="form-select" required>
                    <option value="" selected disabled>Pilih Klasifikasi Pengaduan</option>
                    @foreach ($klasifikasiPengaduan as $klasifikasi)
                        <option value="{{ $klasifikasi->ID_KLASIFIKASI }}">
                            {{ $klasifikasi->KLASIFIKASI_PENGADUAN }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3" id="wrapper_nama">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" class="form-control" placeholder="Masukkan nama lengkap anda" name="NAME" required>
            </div>

            <div class="mb-3" id="wrapper_no_tlpn">
                <label for="nomorTelepon" class="form-label fw-bold">Nomor Telepon</label>
                <input type="tel" class="form-control" id="nomorTelepon" name="NO_TLPN"
                    placeholder="Contoh: 08123456789" required maxlength="15" pattern="^08\d{8,13}$" inputmode="numeric">
                <div class="form-text">Nomor telepon harus diawali dengan "08" dan terdiri dari 10-15 digit angka.</div>
            </div>

            <div class="mb-3" id="wrapper_no_medrec">
                <label class="form-label fw-bold" for="nomorRekamMedis">Nomor Rekam Medis (Opsional)</label>
                <input type="text" class="form-control" id="nomorRekamMedis"
                    placeholder="Masukkan nomor rekam medis jika ada" name="NO_MEDREC">
            </div>

            <div class="mb-3" id="wrapper_deskripsi">
                <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                <textarea class="form-control" rows="4" placeholder="Jelaskan secara detail pengaduan anda" name="ISI_COMPLAINT"
                    required></textarea>
            </div>

            <div class="mb-4" id="wrapper_bukti">
                <label for="buktiPendukungFile" class="form-label fw-bold" id="buktiPendukungLabel">Bukti Pendukung
                    (Opsional)</label>
                <input type="file" id="buktiPendukungFile" name="bukti_pendukung[]" class="d-none"
                    accept=".jpg, .jpeg, .png, .pdf" multiple>
                <label id="buktiPendukungDropZone" for="buktiPendukungFile" class="upload-box d-block"
                    style="cursor: pointer;">
                    <div class="upload-box-content"></div>
                </label>
                <div id="buktiPendukungFileErrors" class="text-danger mt-2"></div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-simpan">Kirim Laporan</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4.5rem;"></i>
                    </div>
                    <h5 class="mb-2" id="modalTicketNumber">Nomor tiket anda:</h5>
                    <p class="text-muted">Simpan nomor tiket ini untuk melacak status laporan anda.</p>
                    <button type="button" class="btn btn-simpan mt-2" id="modalOkButton">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection
