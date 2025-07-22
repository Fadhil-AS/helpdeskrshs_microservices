document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalTambahPengaduan');
    if (!modal) return;

    // --- Deklarasi semua elemen di dalam modal ---
    const klasifikasiSelect = modal.querySelector('#ID_KLASIFIKASI');
    const jenisPelaporSelect = modal.querySelector('#jenisPelapor');

    const namaWrapper = modal.querySelector('#wrapper_nama');
    const noTlpnWrapper = modal.querySelector('#wrapper_no_tlpn');
    const noMedrecWrapper = modal.querySelector('#wrapper_no_medrec');

    const namaLabel = modal.querySelector('#wrapper_nama .form-label');
    const noTlpnLabel = modal.querySelector('#wrapper_no_tlpn .form-label');
    const noMedrecLabel = modal.querySelector('#wrapper_no_medrec .form-label');
    const fileLabel = modal.querySelector('label[for="FILE_PENGADUAN_input"]');

    const namaInput = modal.querySelector('#NAME');
    const noTlpnInput = modal.querySelector('#NO_TLPN');
    const noMedrecInput = modal.querySelector('#nomorRekamMedis');
    const fileInput = modal.querySelector('#FILE_PENGADUAN_input');
    const sponsorshipOption = Array.from(klasifikasiSelect.options).find(opt => opt.text.trim().toLowerCase() === 'sponsorship');


    function updateFormState() {
        if (!klasifikasiSelect || !jenisPelaporSelect) return;

        let selectedKlasifikasi = klasifikasiSelect.options[klasifikasiSelect.selectedIndex]?.text.trim().toLowerCase() || '';
        let selectedPelapor = jenisPelaporSelect.value;

        // Sponsorship
        if (selectedKlasifikasi === 'sponsorship') {
            // Jika klasifikasi adalah "Sponsorship":
            jenisPelaporSelect.value = 'Non-Pasien';
            selectedPelapor = 'Non-Pasien';

            jenisPelaporSelect.style.pointerEvents = 'none';
            jenisPelaporSelect.style.backgroundColor = '#e9ecef';

        } else {
            // Jika klasifikasi BUKAN "Sponsorship":
            jenisPelaporSelect.style.pointerEvents = 'auto';
            jenisPelaporSelect.style.backgroundColor = '';
        }

        // Pasien dipilih
        if (selectedPelapor === 'Pasien' && selectedKlasifikasi === 'sponsorship') {
             klasifikasiSelect.value = '';
             selectedKlasifikasi = '';
        }

        // Atur status enable/disable dropdown
        // jenisPelaporSelect.disabled = (selectedKlasifikasi === 'sponsorship');
        if (sponsorshipOption) {
            sponsorshipOption.disabled = (selectedPelapor === 'Pasien');
            if (selectedPelapor === 'Pasien' && selectedKlasifikasi === 'sponsorship') {
                klasifikasiSelect.value = '';
                return updateFormState();
            }
        }

        // Atur visibilitas wrapper
        const isGratifikasi = selectedKlasifikasi === 'gratifikasi';
        const isSponsorship = selectedKlasifikasi === 'sponsorship';
        // const isEtik = selectedKlasifikasiText === 'etik';

        if (namaWrapper) namaWrapper.style.display = isGratifikasi ? 'none' : 'block';
        if (noTlpnWrapper) noTlpnWrapper.style.display = isGratifikasi ? 'none' : 'block';
        if (noMedrecWrapper) noMedrecWrapper.style.display = (isGratifikasi || isSponsorship) ? 'none' : 'block';

        // Atur label dan status 'required'
        if (namaInput) {
            namaInput.required = !isGratifikasi && !isSponsorship;
            namaLabel.innerHTML = namaInput.required ? 'Nama Pelapor' : 'Nama Pelapor (Opsional)';
        }
        if (noTlpnInput) {
            noTlpnInput.required = !isGratifikasi && !isSponsorship;
            noTlpnLabel.innerHTML = noTlpnInput.required ? 'Nomor Telepon' : 'Nomor Telepon (Opsional)';
        }
        if (noMedrecInput) {
            noMedrecInput.required = !isGratifikasi && !isSponsorship && selectedPelapor === 'Pasien';
            noMedrecLabel.innerHTML = noMedrecInput.required ? 'Nomor Rekam Medis (Wajib)' : 'Nomor Rekam Medis (Opsional)';
        }
        if (fileInput) {
            fileInput.required = isGratifikasi || isSponsorship;
            if (isGratifikasi) fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';
            else if (isSponsorship) fileLabel.innerHTML = 'Surat Undangan (Wajib)';
            else fileLabel.innerHTML = 'File Pengaduan (jika ada)';
        }
    }

    if (klasifikasiSelect) {
        klasifikasiSelect.addEventListener('change', updateFormState);
    }
    if (jenisPelaporSelect) {
        jenisPelaporSelect.addEventListener('change', updateFormState);
    }

    modal.addEventListener('show.bs.modal', function () {

        const form = modal.querySelector('form');
        if (form) form.reset();

        updateFormState();
        jenisPelaporSelect.disabled = false;
    });
});
