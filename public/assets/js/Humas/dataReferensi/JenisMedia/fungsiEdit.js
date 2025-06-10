document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('body');

    function enterEditMode(row) {
        const viewMode = row.querySelector('.view-mode-actions');
        const editMode = row.querySelector('.edit-mode-actions');
        const cell = row.querySelector('.editable-text')?.closest('td');
        if (!cell) return;

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
        const cell = row.querySelector('.editable-text')?.closest('td');
        if (!cell) return;

        const textSpan = cell.querySelector('.editable-text');
        const inputField = cell.querySelector('.editable-input');

        viewMode.classList.remove('d-none');
        editMode.classList.add('d-none');
        textSpan.classList.remove('d-none');
        inputField.classList.add('d-none');
        inputField.value = textSpan.innerText;
    }

    container.addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (!target) return;

        const table = target.closest('.table');
        if (!table) return;

        const row = target.closest('tr');

        if (target.classList.contains('btn-inline-edit')) {
            event.preventDefault();
            enterEditMode(row);
        }
        if (target.classList.contains('btn-inline-cancel')) {
            event.preventDefault();
            exitEditMode(row);
        }
        if (target.classList.contains('btn-inline-save')) {
            event.preventDefault();
            const url = target.dataset.url;
            const inputField = row.querySelector('.editable-input');
            const dataKey = inputField.name;

            if (!dataKey) {
                console.error("Error: Input field tidak memiliki atribut 'name'.");
                return;
            }

            const newValue = inputField.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const originalValue = row.querySelector('.editable-text').textContent.trim();

            if (newValue.trim().toUpperCase() === originalValue.toUpperCase()) {
                exitEditMode(row);
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
                if (data && data.success) {
                    const textSpan = row.querySelector('.editable-text');
                    textSpan.innerText = newValue.toUpperCase();
                } else {
                    alert(data.message || 'Data yang Anda masukkan sudah ada atau tidak valid.');
                }
                exitEditMode(row);
            }).catch(error => {
                console.error('Error:', error);
                exitEditMode(row);
            });
        }
    });
});
