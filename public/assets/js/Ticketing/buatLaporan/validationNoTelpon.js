document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('nomorTelepon');
    const phoneErrorDiv = document.getElementById('nomorTeleponError');

    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            let value = e.target.value;
            value = value.replace(/\D/g, '');
            if (value.length > 15) {
                value = value.slice(0, 15);
            }
            e.target.value = value;

            phoneInput.setCustomValidity('');
            phoneInput.classList.remove('is-invalid');
            if (phoneErrorDiv) phoneErrorDiv.textContent = '';
        });

        phoneInput.addEventListener('blur', function () {
            validatePhoneNumberField(phoneInput, phoneErrorDiv);
        });

        const form = phoneInput.closest('form');
        if (form) {
            form.addEventListener('submit', function (event) {
                if (!validatePhoneNumberField(phoneInput, phoneErrorDiv)) {
                    event.preventDefault();
                    setTimeout(() => phoneInput.focus(), 0);
                }
            });
        }
    }
});

function validatePhoneNumberField(inputElement, errorDisplayElement) {
    // Bersihkan status validasi dan pesan error sebelumnya
    inputElement.classList.remove('is-invalid', 'is-valid');
    if (errorDisplayElement) errorDisplayElement.textContent = '';
    inputElement.setCustomValidity('');

    const value = inputElement.value.trim();

    // Pola: diawali "08", diikuti 8 sampai 13 digit angka (total 10-15 digit)
    const pattern = /^08\d{8,13}$/;

    // Jika field required dan kosong
    if (inputElement.required && value === '') {
        if (!inputElement.checkValidity()) {
            inputElement.classList.add('is-invalid');
            if (errorDisplayElement && inputElement.validationMessage) {
                errorDisplayElement.textContent = inputElement.validationMessage;
            }
            return false;
        }
        return true;
    }

    // Jika field tidak required dan kosong, anggap valid
    if (!inputElement.required && value === '') {
        return true;
    }

    // Validasi dengan pola regex
    if (!pattern.test(value)) {
        let customMessage = 'Format nomor telepon tidak valid.';
        if (value.length < 2 || !value.startsWith('08')) {
            customMessage = 'Nomor telepon harus diawali dengan "08".';
        } else if (value.length < 9) {
            customMessage = 'Nomor telepon terlalu pendek (minimal 9 digit).';
        } else if (value.length > 15) {
            customMessage = 'Nomor telepon terlalu panjang (maksimal 15 digit).';
        } else if (/\D/.test(value)) {
            customMessage = 'Nomor telepon hanya boleh berisi angka.';
        }

        inputElement.setCustomValidity(customMessage);
        inputElement.classList.add('is-invalid');
        if (errorDisplayElement) errorDisplayElement.textContent = customMessage;
        return false;
    }

    // Jika semua validasi lolos
    inputElement.classList.add('is-valid');
    return true;
}
