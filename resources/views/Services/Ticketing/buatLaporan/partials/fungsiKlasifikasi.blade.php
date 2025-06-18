<script>
    document.addEventListener('DOMContentLoaded', function() {
        function handleKlasifikasiChange() {
            const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
            const fileLabel = document.getElementById('buktiPendukungLabel');
            if (!klasifikasiSelect || !fileLabel) return;

            // const fileInput = document.getElementById('buktiPendukungLabel');
            const selectedOptionText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim()
                .toLowerCase();
            // const fileLabel = document.querySelector('label[for="buktiPendukungFile"]');


            const wrapperNama = document.getElementById('wrapper_nama');
            const wrapperNoTlpn = document.getElementById('wrapper_no_tlpn');
            const wrapperNoMedrec = document.getElementById('wrapper_no_medrec');
            const inputNama = document.querySelector('[name="NAME"]');
            const inputNoTlpn = document.querySelector('[name="NO_TLPN"]');

            if (wrapperNama) wrapperNama.style.display = 'block';
            if (wrapperNoTlpn) wrapperNoTlpn.style.display = 'block';
            if (wrapperNoMedrec) wrapperNoMedrec.style.display = 'block';
            if (inputNama) inputNama.required = true;
            if (inputNoTlpn) inputNoTlpn.required = true;

            // const fieldsToToggle = [
            //     'wrapper_nama',
            //     'wrapper_no_tlpn',
            //     'wrapper_no_medrec',
            // ];
            // const inputsToToggleRequired = [
            //     'NAME',
            //     'NO_TLPN',
            // ];

            fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';
            if (selectedOptionText === 'sponsorship') {
                fileLabel.innerHTML = 'Surat Undangan (Wajib)';
                // Sembunyikan hanya Nomor Rekam Medis
                if (wrapperNoMedrec) wrapperNoMedrec.style.display = 'none';

            } else if (selectedOptionText === 'gratifikasi') {
                fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';

                // Sembunyikan Nama, No Telp, dan No Medrec
                if (wrapperNama) wrapperNama.style.display = 'none';
                if (wrapperNoTlpn) wrapperNoTlpn.style.display = 'none';
                if (wrapperNoMedrec) wrapperNoMedrec.style.display = 'none';

                // Jadikan tidak wajib (penting untuk validasi form)
                if (inputNama) inputNama.required = false;
                if (inputNoTlpn) inputNoTlpn.required = false;
            }

            // fileInput.required = false;
            // fieldsToToggle.forEach(id => {
            //     const wrapper = document.getElementById(id);
            //     if (wrapper) wrapper.style.display = 'block';
            // });
            // inputsToToggleRequired.forEach(name => {
            //     const input = document.querySelector(`[name="${name}"]`);
            //     if (input) input.required = true;
            // });

            // if (selectedOptionText === 'sponsorship' || selectedOptionText === 'gratifikasi') {
            //     fileLabel.innerHTML = selectedOptionText === 'sponsorship' ? 'Surat Undangan (Wajib)' :
            //         'Bukti Pendukung (Wajib)';

            //     if (selectedOptionText === 'gratifikasi') {
            //         fieldsToToggle.forEach(id => {
            //             const wrapper = document.getElementById(id);
            //             if (wrapper) wrapper.style.display = 'none';
            //         });
            //         inputsToToggleRequired.forEach(name => {
            //             const input = document.querySelector(`[name="${name}"]`);
            //             if (input) input.required = false;
            //         });
            //     }
            // }


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
