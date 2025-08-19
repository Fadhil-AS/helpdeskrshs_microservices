// document.addEventListener('DOMContentLoaded', function () {
//     const filterForm = document.getElementById('filterForm');
//     const tableBody = document.getElementById('complaintTableBody');
//     const paginationContainer = document.getElementById('paginationContainer');
//     const allInputs = filterForm.querySelectorAll('input, select');

//     let typingTimer;
//     const doneTypingInterval = 100;

//     function performSearch(url = null) {
//         if (!url) {
//             const formData = new FormData(filterForm);
//             const params = new URLSearchParams(formData);
//             url = `${filterForm.action}?${params.toString()}`;
//         }

//         fetch(url)
//             .then(response => response.text())
//             .then(html => {
//                 const parser = new DOMParser();
//                 const doc = parser.parseFromString(html, 'text/html');

//                 const newTbody = doc.getElementById('complaintTableBody');
//                 const newPagination = doc.getElementById('paginationContainer');

//                 if (newTbody) {
//                     tableBody.innerHTML = newTbody.innerHTML;
//                 }
//                 if (newPagination) {
//                     paginationContainer.innerHTML = newPagination.innerHTML;
//                 }
//             })
//             .catch(error => console.error('Error fetching results:', error));
//     }

//     allInputs.forEach(element => {
//         if (element.type === 'text') {
//             element.addEventListener('keyup', () => {
//                 clearTimeout(typingTimer);
//                 typingTimer = setTimeout(() => performSearch(), doneTypingInterval);
//             });
//         } else {
//             element.addEventListener('change', () => performSearch());
//         }
//     });


//     document.body.addEventListener('click', function(e) {
//         if (e.target.matches('#paginationContainer a.page-link')) {
//             e.preventDefault();
//             const url = e.target.href;
//             if (url) {
//                 performSearch(url);
//             }
//         }
//     });

//     filterForm.addEventListener('submit', (e) => e.preventDefault());
// });



    // --- SCRIPT FILTER DAN EKSPOR BARU ---
// Di dalam file .js eksternal Anda

document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua data dan URL dari objek "jembatan"
    const filterForm = document.getElementById('filterForm');
    const tableBody = document.getElementById('complaintTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const allInputs = filterForm.querySelectorAll('input, select');

    const periodeFilter = document.getElementById('filterPeriode');
    const tahunFilter = document.getElementById('filterTahun');
    const bulanFilter = document.getElementById('filterBulan');
    const triwulanFilter = document.getElementById('filterTriwulan');
    const semesterFilter = document.getElementById('filterSemester');

    const exportPdfBtn = document.getElementById('exportPdfBtn');
    const exportExcelBtn = document.getElementById('exportExcelBtn');

    let typingTimer;
    const doneTypingInterval = 500;

    function performSearch(url = null) {
        if (!url) {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            url = `${filterForm.action}?${params.toString()}`;
        }
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.getElementById('complaintTableBody');
                const newPagination = doc.getElementById('paginationContainer');
                if (newTbody) tableBody.innerHTML = newTbody.innerHTML;
                if (newPagination) paginationContainer.innerHTML = newPagination.innerHTML;
            })
            .catch(error => console.error('Error fetching results:', error));
    }

    function toggleSecondaryFilters() {
        const selectedPeriode = periodeFilter.value;
        [tahunFilter, bulanFilter, triwulanFilter, semesterFilter].forEach(el => el.classList.add('d-none'));
        if (selectedPeriode) {
            tahunFilter.classList.remove('d-none');
            if (selectedPeriode === 'bulan') bulanFilter.classList.remove('d-none');
            else if (selectedPeriode === 'triwulan') triwulanFilter.classList.remove('d-none');
            else if (selectedPeriode === 'semester') semesterFilter.classList.remove('d-none');
        }
    }

    allInputs.forEach(element => {
        if (element.type === 'text') {
            element.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => performSearch(), doneTypingInterval);
            });
        } else {
            element.addEventListener('change', () => performSearch());
        }
    });

    periodeFilter.addEventListener('change', toggleSecondaryFilters);

    document.body.addEventListener('click', function(e) {
        if (e.target.closest('#paginationContainer a.page-link')) {
            e.preventDefault();
            performSearch(e.target.closest('a').href);
        }
    });

    // --- FUNGSI EKSPOR YANG DIPERBARUI ---
    function handleExport(exportType) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        let baseUrl = '';
        if (exportType === 'pdf') {
            // Gunakan URL yang sudah disiapkan oleh Blade
            baseUrl = window.pageData.pdfExportUrl;
        } else if (exportType === 'excel') {
            // Gunakan URL yang sudah disiapkan oleh Blade
            baseUrl = window.pageData.excelExportUrl;
        }

        const exportUrl = `${baseUrl}?${params.toString()}`;
        window.location.href = exportUrl;
    }

    exportPdfBtn.addEventListener('click', (e) => {
        e.preventDefault();
        handleExport('pdf');
    });

    exportExcelBtn.addEventListener('click', (e) => {
        e.preventDefault();
        handleExport('excel');
    });

    filterForm.addEventListener('submit', (e) => e.preventDefault());
    toggleSecondaryFilters();
});
