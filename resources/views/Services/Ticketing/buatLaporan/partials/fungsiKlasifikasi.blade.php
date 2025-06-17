<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menangani perubahan pada dropdown klasifikasi
        function handleKlasifikasiChange() {
            const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
            if (!klasifikasiSelect) return;

            const fileInput = document.getElementById('buktiPendukungFile');
            const fileLabel = document.querySelector('label[for="buktiPendukungFile"]');

            const namaWrapper = document.getElementById('wrapper_nama');
            const noTlpnWrapper = document.getElementById('wrapper_no_tlpn');
            const noMedrecWrapper = document.getElementById('wrapper_no_medrec');

            const namaInput = document.querySelector('[name="NAME"]');
            const noTlpnInput = document.querySelector('[name="NO_TLPN"]');

            const selectedOptionText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim().toLowerCase();

            // --- 1. Reset semua field ke kondisi default terlebih dahulu ---
            fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';
            fileInput.required = false;

            if (namaWrapper) namaWrapper.style.display = '';
            if (noTlpnWrapper) noTlpnWrapper.style.display = '';
            if (noMedrecWrapper) noMedrecWrapper.style.display = '';

            if (namaInput) namaInput.required = true;
            if (noTlpnInput) noTlpnInput.required = true;


            // --- 2. Terapkan logika baru berdasarkan pilihan ---
            if (selectedOptionText === 'sponsorship') {
                // SPONSORSHIP
                fileLabel.innerHTML = 'Surat Undangan (Wajib)';
                fileInput.required = true;

                // Sembunyikan field Nomor Rekam Medis
                if (noMedrecWrapper) {
                    noMedrecWrapper.style.display = 'none';
                }

            } else if (selectedOptionText === 'gratifikasi') {
                // GRATIFIKASI
                fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';
                fileInput.required = true;

                // Sembunyikan field nama, no telepon, dan no medrec
                if (namaWrapper) namaWrapper.style.display = 'none';
                if (noTlpnWrapper) noTlpnWrapper.style.display = 'none';
                if (noMedrecWrapper) noMedrecWrapper.style.display = 'none';

                // Jadikan inputnya tidak wajib diisi
                if (namaInput) namaInput.required = false;
                if (noTlpnInput) noTlpnInput.required = false;
            }
        }

        const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
        if (klasifikasiSelect) {
            klasifikasiSelect.addEventListener('change', handleKlasifikasiChange);

            handleKlasifikasiChange();
        }
    });
</script>
