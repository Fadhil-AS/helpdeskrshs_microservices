<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisPelaporSelect = document.getElementById('jenisPelapor');

        // Fungsi untuk menangani perubahan pada jenis pelapor (Pasien/Non-Pasien)
        function handleJenisPelaporChange() {
            if (!jenisPelaporSelect) return;

            const noMedrecLabel = document.querySelector('label[for="nomorRekamMedis"]');
            const noMedrecInput = document.getElementById('nomorRekamMedis');

            // Pastikan elemen-elemennya ada sebelum dimanipulasi
            if (!noMedrecLabel || !noMedrecInput) return;

            const selectedPelapor = jenisPelaporSelect.value;

            if (selectedPelapor === 'Pasien') {
                noMedrecLabel.innerHTML = 'Nomor Rekam Medis (Wajib)';
                noMedrecInput.required = true;
            } else {
                noMedrecLabel.innerHTML = 'Nomor Rekam Medis (Opsional)';
                noMedrecInput.required = false;
            }
        }

        if (jenisPelaporSelect) {
            jenisPelaporSelect.addEventListener('change', handleJenisPelaporChange);

            handleJenisPelaporChange();
        }
    });
</script>
