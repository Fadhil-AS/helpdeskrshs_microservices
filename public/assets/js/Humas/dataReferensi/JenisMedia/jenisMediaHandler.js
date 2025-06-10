document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('#tabel-jenis-media-container');

    if (!container) return;

    container.addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (!target) return;

        if (target.classList.contains('btn-inline-save')) {
            event.preventDefault();

            event.stopImmediatePropagation();

            const row = target.closest('tr');
            const url = target.dataset.url;
            const inputField = row.querySelector('.editable-input');
            const dataKey = inputField.name;
            const newValue = inputField.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (newValue.trim() === "") {
                alert("Kolom tidak boleh kosong.");
                return;
            }

            const bodyData = {};
            bodyData[dataKey] = newValue;

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(bodyData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.message) {
                    alert(data.message);
                }
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi ke server.');
                location.reload();
            });
        }
    });
});
