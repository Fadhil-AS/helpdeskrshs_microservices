document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    if (!searchForm) {
        return;
    }

    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('unit-kerja-table-body');
    const paginationLinks = document.getElementById('pagination-links');
    const searchUrl = searchForm.dataset.searchUrl;
    let debounceTimer;

    // Fungsi utama untuk mengambil data via AJAX (tidak ada perubahan)
    async function fetchData(url) {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            tableBody.innerHTML = data.table_html;
            paginationLinks.innerHTML = data.pagination_html;
        } catch (error) {
            console.error('Error fetching data:', error);
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Terjadi kesalahan saat memuat data.</td></tr>';
        }
    }

    // Event listener untuk input pencarian (tidak ada perubahan)
    searchInput.addEventListener('keyup', function () {
        clearTimeout(debounceTimer);
        const query = this.value;

        debounceTimer = setTimeout(() => {
            const url = `${searchUrl}?search=${encodeURIComponent(query)}`;
            fetchData(url);
        }, 300);
    });

    // Event listener untuk klik pada link paginasi (tidak ada perubahan)
    paginationLinks.addEventListener('click', function (event) {
        if (event.target.tagName === 'A' && event.target.closest('.pagination')) {
            event.preventDefault();
            const url = event.target.getAttribute('href');
            if (url) {
                fetchData(url);
            }
        }
    });

    // Logika untuk EXPAND / COLLAPSE (dengan perbaikan)
    tableBody.addEventListener('click', function(event) {
        const parentRow = event.target.closest('tr[data-child]');
        if (!parentRow || !parentRow.querySelector('td:first-child').contains(event.target)) {
            return;
        }

        const childGroup = parentRow.dataset.child;
        const childRows = document.querySelectorAll(`.child-row.${childGroup}`);
        const toggleIcon = parentRow.querySelector('.toggle-icon');

        if (childRows.length > 0 && toggleIcon) {
            const isExpanded = parentRow.classList.contains('expanded');

            if (isExpanded) {
                parentRow.classList.remove('expanded');
                toggleIcon.textContent = '▸';
                document.querySelectorAll(`.child-row`).forEach(row => {
                    // ✅ PERBAIKAN: Tambahkan kondisi 'row !== parentRow' untuk memastikan
                    // kita tidak menyembunyikan baris yang sedang diklik.
                    if (row !== parentRow && row.dataset.child.startsWith(childGroup)) {
                        row.style.display = 'none';
                        row.classList.remove('expanded');
                        const icon = row.querySelector('.toggle-icon');
                        if(icon) icon.textContent = '▸';
                    }
                });
            } else {
                parentRow.classList.add('expanded');
                toggleIcon.textContent = '▾';
                childRows.forEach(row => {
                    row.style.display = 'table-row';
                });
            }
        }
    });
});
