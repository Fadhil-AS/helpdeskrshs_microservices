document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('.table tbody');

    function enterEditMode(row) {
        const viewMode = row.querySelector('.view-mode-actions');
        const editMode = row.querySelector('.edit-mode-actions');
        const cell = row.querySelector('.klasifikasi-cell');
        const textSpan = cell.querySelector('.editable-text');
        const inputField = cell.querySelector('.editable-input');

        viewMode.classList.add('d-none');
        editMode.classList.remove('d-none');
        textSpan.classList.add('d-none');
        inputField.classList.remove('d-none');
        inputField.focus();
    }

    function exitEditMode(row) {
        const viewMode = row.querySelector('.view-mode-actions');
        const editMode = row.querySelector('.edit-mode-actions');
        const cell = row.querySelector('.klasifikasi-cell');
        const textSpan = cell.querySelector('.editable-text');
        const inputField = cell.querySelector('.editable-input');

        viewMode.classList.remove('d-none');
        editMode.classList.add('d-none');
        textSpan.classList.remove('d-none');
        inputField.classList.add('d-none');
        inputField.value = textSpan.innerText;
    }

    tableBody.addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (!target) return;

        event.preventDefault();
        const row = target.closest('tr');

        if (target.classList.contains('btn-inline-edit')) {
            enterEditMode(row);
        }
        if (target.classList.contains('btn-inline-cancel')) {
            exitEditMode(row);
        }
        if (target.classList.contains('btn-inline-save')) {
            const url = target.dataset.url;
            const inputField = row.querySelector('.editable-input');
            const newValue = inputField.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const originalValue = row.querySelector('.editable-text').textContent.trim();
            if (newValue.trim().toUpperCase() === originalValue.toUpperCase()) {
                exitEditMode(row);
                return;
            }

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    KLASIFIKASI_PENGADUAN: newValue
                })
            })
            .then(response => {
                console.log("Status Respons Server:", response.status);

                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    const textSpan = row.querySelector('.editable-text');
                    textSpan.innerText = newValue.toUpperCase();
                    alert(data.message);
                    exitEditMode(row);
                    location.reload();
                }
                else {
                    alert(data.message || 'Data yang Anda masukkan sudah ada atau tidak valid.');
                    exitEditMode(row);
                    location.reload();
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Tidak dapat terhubung ke server atau terjadi error.');
                exitEditMode(row);
            });
        }
    });
});
