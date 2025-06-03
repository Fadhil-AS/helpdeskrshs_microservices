document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector('.input-group input');
    const statusSelect = document.querySelector('.form-select');
    const resetButton = document.querySelector('.btn-reset');
    const rows = document.querySelectorAll('tbody tr');
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusSelect.value;
        rows.forEach(row => {
            const title = row.children[2].textContent.toLowerCase();
            const status = row.children[5].textContent.trim();
            const matchesSearch = title.includes(searchTerm);
            const matchesStatus = selectedStatus === "Semua Status" || status === selectedStatus;
            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', filterTable);
    statusSelect.addEventListener('change', filterTable);
    resetButton.addEventListener('click', () => {
        searchInput.value = '';
        statusSelect.value = 'Semua Status';
        filterTable();
    });
});
