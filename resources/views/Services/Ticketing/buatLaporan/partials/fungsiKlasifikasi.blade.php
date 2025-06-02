<script>
    document.addEventListener('DOMContentLoaded', function() {
        const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');

        // Daftar ID wrapper field yang akan diatur
        const fieldsToToggle = [
            'wrapper_nama',
            'wrapper_no_tlpn',
            'wrapper_no_medrec',
            // 'wrapper_bukti'
        ];

        // Daftar field yang persyaratannya diubah (required/tidak)
        const inputsToToggleRequired = [
            'NAME',
            'NO_TLPN'
        ];

        function toggleGratifikasiFields() {
            // Ambil teks dari opsi yang dipilih
            const selectedOptionText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text;

            // Cek apakah teksnya adalah "Gratifikasi" (sesuaikan jika teksnya berbeda)
            const isGratifikasi = selectedOptionText.trim().toLowerCase() === 'gratifikasi';

            // Lakukan loop untuk menyembunyikan atau menampilkan field
            fieldsToToggle.forEach(fieldId => {
                const wrapper = document.getElementById(fieldId);
                if (wrapper) {
                    wrapper.style.display = isGratifikasi ? 'none' : '';
                }
            });

            // Lakukan loop untuk mengubah status 'required' pada input
            inputsToToggleRequired.forEach(inputName => {
                const inputElement = document.querySelector(`[name="${inputName}"]`);
                if (inputElement) {
                    inputElement.required = !isGratifikasi;
                }
            });

            // Field referensi tiket dan deskripsi selalu terlihat, jadi tidak perlu diapa-apakan.
        }

        // Tambahkan event listener untuk memanggil fungsi saat pilihan berubah
        if (klasifikasiSelect) {
            klasifikasiSelect.addEventListener('change', toggleGratifikasiFields);

            // Panggil fungsi sekali saat halaman dimuat untuk menangani kasus old value dari server
            toggleGratifikasiFields();
        }
    });
</script>
