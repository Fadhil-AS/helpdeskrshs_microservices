<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menangani perubahan pada dropdown klasifikasi
        function handleKlasifikasiChange() {
            const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
            if (!klasifikasiSelect) return;

            // Ambil elemen-elemen yang akan diubah
            const fileInput = document.getElementById('buktiPendukungFile');
            const fileLabel = document.querySelector('label[for="buktiPendukungFile"]');
            const selectedOptionText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim().toLowerCase();

            // Daftar field yang disembunyikan/ditampilkan untuk kasus Gratifikasi
            const fieldsToToggle = [
                'wrapper_nama',
                'wrapper_no_tlpn',
                'wrapper_no_medrec',
            ];
            const inputsToToggleRequired = [
                'NAME',
                'NO_TLPN',
            ];

            // Reset semua field ke kondisi default terlebih dahulu
            fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';
            fileInput.required = false;
            fieldsToToggle.forEach(id => {
                const wrapper = document.getElementById(id);
                if(wrapper) wrapper.style.display = '';
            });
            inputsToToggleRequired.forEach(name => {
                const input = document.querySelector(`[name="${name}"]`);
                if(input) input.required = true;
            });


            // Terapkan logika berdasarkan pilihan
            if (selectedOptionText === 'sponsorship') {
                // SPONSORSHIP
                fileLabel.innerHTML = 'Surat Undangan (Wajib)';
                fileInput.required = true;

            } else if (selectedOptionText === 'gratifikasi') {
                // GRATIFIKASI
                fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';
                fileInput.required = true;

                // Sembunyikan field lain dan hapus 'required'-nya
                fieldsToToggle.forEach(fieldId => {
                    const wrapper = document.getElementById(fieldId);
                    if (wrapper) {
                        wrapper.style.display = 'none';
                    }
                });
                inputsToToggleRequired.forEach(inputName => {
                    const inputElement = document.querySelector(`[name="${inputName}"]`);
                    if (inputElement) {
                        inputElement.required = false;
                    }
                });
            }

        }

        // Tambahkan event listener untuk memanggil fungsi saat pilihan berubah
        const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
        if (klasifikasiSelect) {
            klasifikasiSelect.addEventListener('change', handleKlasifikasiChange);

            handleKlasifikasiChange();
        }
    });
</script>
