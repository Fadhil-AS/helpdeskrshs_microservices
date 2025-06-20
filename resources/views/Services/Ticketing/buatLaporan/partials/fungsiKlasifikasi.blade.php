{{-- File: fungsiKlasifikasi.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
    const form = document.getElementById('formPengaduan');

    // Fungsi untuk menangani perubahan pada dropdown klasifikasi
    function handleKlasifikasiChange() {
        if (!klasifikasiSelect) return;

        const fileInput = document.getElementById('buktiPendukungFile');
        const fileLabel = document.querySelector('label[for="buktiPendukungFile"]');
        const selectedKlasifikasi = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim().toLowerCase();

        const namaWrapper = document.getElementById('wrapper_nama');
        const noTlpnWrapper = document.getElementById('wrapper_no_tlpn');
        const noMedrecWrapper = document.getElementById('wrapper_no_medrec');

        const namaLabel = document.querySelector('#wrapper_nama .form-label');
        const noTlpnLabel = document.querySelector('#wrapper_no_tlpn .form-label');

        const namaInput = document.querySelector('[name="NAME"]');
        const noTlpnInput = document.querySelector('[name="NO_TLPN"]');
        const noMedrecInput = document.getElementById('nomorRekamMedis');

        if (namaWrapper) namaWrapper.style.display = 'block';
        if (noTlpnWrapper) noTlpnWrapper.style.display = 'block';
        if (noMedrecWrapper) noMedrecWrapper.style.display = 'block';

        if (namaLabel) namaLabel.innerHTML = 'Nama Lengkap';
        if (noTlpnLabel) noTlpnLabel.innerHTML = 'Nomor Telepon';

        if (namaInput) namaInput.required = true;
        if (noTlpnInput) noTlpnInput.required = true;

        fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';
        fileInput.required = false;

        // logika khusus untuk setiap klasifikasi
        if (selectedKlasifikasi === 'sponsorship') {
            // SPONSORSHIP
            fileLabel.innerHTML = 'Surat Undangan (Wajib)';
            fileInput.required = true;

            if (noMedrecWrapper) noMedrecWrapper.style.display = 'none';
            if (noMedrecInput) noMedrecInput.required = false;

            if (namaLabel) namaLabel.innerHTML = 'Nama Lengkap (Opsional)';
            if (noTlpnLabel) noTlpnLabel.innerHTML = 'Nomor Telepon (Opsional)';
            if (namaInput) namaInput.required = false;
            if (noTlpnInput) noTlpnInput.required = false;

        } else if (selectedKlasifikasi === 'gratifikasi') {
            // GRATIFIKASI
            fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';
            fileInput.required = true;

            const fieldsToHide = [namaWrapper, noTlpnWrapper, noMedrecWrapper];
            const inputsToMakeOptional = [namaInput, noTlpnInput, noMedrecInput];

            fieldsToHide.forEach(wrapper => { if (wrapper) wrapper.style.display = 'none'; });
            inputsToMakeOptional.forEach(input => { if (input) input.required = false; });
        }
    }

    if (form) {
        form.addEventListener('submit', function(event) {
            const selectedKlasifikasiText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim().toLowerCase();
            const isFileRequired = selectedKlasifikasiText === 'sponsorship' || selectedKlasifikasiText === 'gratifikasi';
            const fileInput = document.getElementById('buktiPendukungFile');
            const fileErrorDiv = document.getElementById('buktiPendukungFileErrors');

            if (isFileRequired && fileInput.files.length === 0) {
                event.preventDefault();
                fileErrorDiv.textContent = 'Anda wajib mengunggah file untuk klasifikasi ini.';
            } else {
                fileErrorDiv.textContent = '';
            }
        });
    }

    if (klasifikasiSelect) {
        klasifikasiSelect.addEventListener('change', handleKlasifikasiChange);
        handleKlasifikasiChange();
    }
});
</script>
