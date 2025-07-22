document.addEventListener('DOMContentLoaded', function() {
    const setupPasswordToggle = (toggleBtnId, passwordInputId) => {
        const togglePassword = document.getElementById(toggleBtnId);
        const password = document.getElementById(passwordInputId);

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
    };

    setupPasswordToggle('toggleNewPassword', 'newPass');
    setupPasswordToggle('toggleConfPassword', 'confPass');

    const form = document.querySelector('form');
    const newPassInput = document.getElementById('newPass');
    const confPassInput = document.getElementById('confPass');

    if (form && newPassInput && confPassInput) {
        form.addEventListener('submit', function(event) {
            const existingError = form.querySelector('.alert-danger');
            if (existingError) {
                existingError.remove();
            }

            if (newPassInput.value !== confPassInput.value) {
                event.preventDefault();

                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger';
                errorAlert.setAttribute('role', 'alert');
                errorAlert.textContent = 'Password baru dan konfirmasi password harus sama.';

                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.parentElement.insertAdjacentElement('beforebegin', errorAlert);
                }
            }
        });
    }
});
