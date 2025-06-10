document.addEventListener('DOMContentLoaded', function() {
    const tables = document.querySelectorAll('.table');
    function switchToEditMode(row) {
        const text = row.querySelector('.editable-text');
        const input = row.querySelector('.editable-input');
        const viewActions = row.querySelector('.view-mode-actions');
        const editActions = row.querySelector('.edit-mode-actions');
        if (text && input && viewActions && editActions) {
            input.value = text.textContent.trim();
            input.dataset.originalValue = text.textContent.trim();
            text.classList.add('d-none');
            input.classList.remove('d-none');
            input.focus();
            input.select();
            viewActions.classList.add('d-none');
            editActions.classList.remove('d-none');
            row.classList.add('editing-row');
        }
    }
    function switchToViewMode(row, newTextValue) {
        const text = row.querySelector('.editable-text');
        const input = row.querySelector('.editable-input');
        const viewActions = row.querySelector('.view-mode-actions');
        const editActions = row.querySelector('.edit-mode-actions');
        if (text && input && viewActions && editActions) {
            if (typeof newTextValue === 'string') {
                text.textContent = newTextValue.toUpperCase();
            }
            text.classList.remove('d-none');
            input.classList.add('d-none');
            viewActions.classList.remove('d-none');
            editActions.classList.add('d-none');
            row.classList.remove('editing-row');
        }
    }
    tables.forEach((table) => {
        table.addEventListener('click', function(event) {
            const clicked = event.target.closest('a');
            if (!clicked) return;
            const row = clicked.closest('tr');
            if (!row) return;
            if (clicked.classList.contains('btn-inline-edit')) {
                event.preventDefault();
                const editingRow = table.querySelector('tr.editing-row');
                if (editingRow && editingRow !== row) {
                    const currentInput = editingRow.querySelector('.editable-input');
                    switchToViewMode(editingRow, currentInput.dataset.originalValue);
                }
                switchToEditMode(row);
            } else if (clicked.classList.contains('btn-inline-save')) {
                event.preventDefault();
                const input = row.querySelector('.editable-input');
                const newValue = input.value.trim();
                if (newValue === "") {
                    alert("Kolom tidak boleh kosong.");
                    input.focus();
                    return;
                }
                const id = row.cells[0]?.textContent.trim();
                console.log(`✅ Simpan perubahan: ID ${id}, Nilai baru: ${newValue}`);
                switchToViewMode(row, newValue);
            } else if (clicked.classList.contains('btn-inline-cancel')) {
                event.preventDefault();
                const input = row.querySelector('.editable-input');
                switchToViewMode(row, input.dataset.originalValue);
            }
        });
    });
});
