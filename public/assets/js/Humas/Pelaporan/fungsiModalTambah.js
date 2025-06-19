document.addEventListener('DOMContentLoaded', function () {
    // --- Elemen yang dikontrol ---
    const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');

    // --- Wrapper (pembungkus) dari input yang akan disembunyikan ---
    const nameWrapper = document.getElementById('name-wrapper');
    const noTlpnWrapper = document.getElementById('no-tlpn-wrapper');
    const noMedrecWrapper = document.getElementById('no-medrec-wrapper');

    // --- Input itu sendiri ---
    const nameInput = document.getElementById('NAME');
    const noTelpInput = document.getElementById('NO_TLPN');
    const noMedrecInput = document.getElementById('NO_MEDREC');

    function togglePelaporFields() {
        if (!klasifikasiSelect || !nameWrapper || !noTlpnWrapper || !noMedrecWrapper || !nameInput || !noTelpInput) {
            return;
        }

        const selectedOption = klasifikasiSelect.options[klasifikasiSelect.selectedIndex];
        const klasifikasiNama = selectedOption ? selectedOption.getAttribute('data-nama') : null;

        const isExceptionalCase = (klasifikasiNama === 'Gratifikasi' || klasifikasiNama === 'Sponsorship');

        // Tampilkan atau sembunyikan wrapper
        nameWrapper.style.display = isExceptionalCase ? 'none' : 'block';
        noTlpnWrapper.style.display = isExceptionalCase ? 'none' : 'block';
        noMedrecWrapper.style.display = isExceptionalCase ? 'none' : 'block';

        // Atur atribut 'required' dan kosongkan nilai jika gratifikasi
        if (isExceptionalCase) {
            nameInput.removeAttribute('required');
            noTelpInput.removeAttribute('required');
            nameInput.value = '';
            noTelpInput.value = '';
            noMedrecInput.value = '';
            if (noMedrecInput) {
                noMedrecInput.value = '';
            }
        } else {
            nameInput.setAttribute('required', 'required');
            noTelpInput.setAttribute('required', 'required');
        }
    }

    // Jalankan fungsi saat dropdown berubah
    if (klasifikasiSelect) {
        klasifikasiSelect.addEventListener('change', togglePelaporFields);
        togglePelaporFields();
    }
});
