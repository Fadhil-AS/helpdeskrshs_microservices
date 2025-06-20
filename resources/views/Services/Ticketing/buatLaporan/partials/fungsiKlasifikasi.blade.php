<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("Memuat script utama: fungsiFormPengaduan.js");

    const form = document.getElementById('formPengaduan');
    if (!form) return;

    // --- Deklarasi semua elemen yang akan diatur ---
    const klasifikasiSelect = form.querySelector('#ID_KLASIFIKASI');
    const jenisPelaporSelect = form.querySelector('#jenisPelapor');

    const namaWrapper = form.querySelector('#wrapper_nama');
    const noTlpnWrapper = form.querySelector('#wrapper_no_tlpn');
    const noMedrecWrapper = form.querySelector('#wrapper_no_medrec');

    const namaLabel = form.querySelector('#wrapper_nama .form-label');
    const noTlpnLabel = form.querySelector('#wrapper_no_tlpn .form-label');
    const noMedrecLabel = form.querySelector('#wrapper_no_medrec .form-label');
    const fileLabel = form.querySelector('label[for="buktiPendukungFile"]');

    const namaInput = form.querySelector('[name="NAME"]');
    const noTlpnInput = form.querySelector('[name="NO_TLPN"]');
    const noMedrecInput = form.querySelector('#nomorRekamMedis');
    const fileInput = form.querySelector('#buktiPendukungFile');
    const sponsorshipOption = Array.from(klasifikasiSelect.options).find(opt => opt.text.trim().toLowerCase() === 'sponsorship');

    function updateFormState() {
        if (!klasifikasiSelect || !jenisPelaporSelect) return;

        let selectedKlasifikasi = klasifikasiSelect.options[klasifikasiSelect.selectedIndex]?.text.trim().toLowerCase() || '';
        let selectedPelapor = jenisPelaporSelect.value;

        // Jika "Sponsorship" dipilih, paksa "Jenis Pelapor" menjadi "Non-Pasien".
        if (selectedKlasifikasi === 'sponsorship' && selectedPelapor !== 'Non-Pasien') {
            jenisPelaporSelect.value = 'Non-Pasien';
            selectedPelapor = 'Non-Pasien';
        }

        // Jika "Pasien" dipilih, nonaktifkan opsi "Sponsorship".
        if (sponsorshipOption) {
            sponsorshipOption.disabled = (selectedPelapor === 'Pasien');
            if (sponsorshipOption.disabled && selectedKlasifikasi === 'sponsorship') {
                 klasifikasiSelect.value = '';
                 selectedKlasifikasi = '';
            }
        }

        const isGratifikasi = selectedKlasifikasi === 'gratifikasi';
        const isSponsorship = selectedKlasifikasi === 'sponsorship';
        const isEtik = selectedKlasifikasi === 'etik';
        const isPasien = selectedPelapor === 'Pasien';

        jenisPelaporSelect.disabled = isSponsorship;

        // Atur visibilitas, label, dan status 'required' untuk setiap field
        // Nama Pelapor & No. Telepon
        const showNamaDanTelepon = !isGratifikasi;
        const requireNamaDanTelepon = !isGratifikasi && !isSponsorship;
        if (namaWrapper) namaWrapper.style.display = showNamaDanTelepon ? 'block' : 'none';
        if (noTlpnWrapper) noTlpnWrapper.style.display = showNamaDanTelepon ? 'block' : 'none';
        if (namaInput) {
            namaInput.required = requireNamaDanTelepon;
            namaLabel.innerHTML = requireNamaDanTelepon ? 'Nama Lengkap' : 'Nama Lengkap (Opsional)';
        }
        if (noTlpnInput) {
            noTlpnInput.required = requireNamaDanTelepon;
            noTlpnLabel.innerHTML = noTlpnInput.required ? 'Nomor Telepon' : 'Nomor Telepon (Opsional)';
        }

        // No. Medrec
        const showMedrec = !isGratifikasi && !isSponsorship;
        const requireMedrec = showMedrec && isPasien;
        if (noMedrecWrapper) noMedrecWrapper.style.display = showMedrec ? 'block' : 'none';
        if (noMedrecInput) {
            noMedrecInput.required = requireMedrec;
            noMedrecLabel.innerHTML = requireMedrec ? 'Nomor Rekam Medis (Wajib)' : 'Nomor Rekam Medis (Opsional)';
        }

        // File Upload
        const requireFile = isGratifikasi || isSponsorship;
        if (fileInput) {
            fileInput.required = requireFile;
            if (isGratifikasi) fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';
            else if (isSponsorship) fileLabel.innerHTML = 'Surat Undangan (Wajib)';
            else fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';
        }
    }

    // --- Mendaftarkan Event Listener ---
    if (klasifikasiSelect) {
        klasifikasiSelect.addEventListener('change', updateFormState);
    }
    if (jenisPelaporSelect) {
        jenisPelaporSelect.addEventListener('change', updateFormState);
    }

    // Panggil fungsi saat halaman pertama kali dimuat untuk mengatur state awal
    updateFormState();
});
</script>
