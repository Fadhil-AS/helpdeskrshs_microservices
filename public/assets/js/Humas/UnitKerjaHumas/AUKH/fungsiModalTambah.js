document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formTambahAdmin');
    const modal = new bootstrap.Modal(document.getElementById('modalTambahAdmin'));

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                for (const field in data.errors) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const errorContainer = input.nextElementSibling;
                        if (errorContainer && errorContainer.classList.contains('invalid-feedback')) {
                            errorContainer.textContent = data.errors[field][0];
                        }
                    }
                }
            } else if (data.success) {
                modal.hide();
                alert(data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    });
});
