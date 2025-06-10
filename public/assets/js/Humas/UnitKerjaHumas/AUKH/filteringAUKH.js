document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');

    if (filterForm) {
        const filterSelects = filterForm.querySelectorAll('select');

        filterSelects.forEach(selectElement => {
            selectElement.addEventListener('change', function() {
                console.log('Filter changed, submitting form...');
                filterForm.submit();
            });
        });
    }
});
