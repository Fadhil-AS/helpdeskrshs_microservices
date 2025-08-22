document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const doneTypingInterval = 100;

    const kategoriTableBody = document.getElementById('kategoriTableBody');
    const kategoriPagination = document.getElementById('kategoriPagination');
    const ssdTableBody = document.getElementById('ssdTableBody');
    const ssdPagination = document.getElementById('ssdPagination');
    const searchKategoriInput = document.getElementById('searchKategoriInput');
    const searchSsdInput = document.getElementById('searchSsdInput');

    function fetchData() {
        const searchKategori = searchKategoriInput.value;
        const searchSsd = searchSsdInput.value;
        const url = `${window.ssdPageData.searchUrl}?search_kategori=${searchKategori}&search_ssd=${searchSsd}`;

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) {
                // Log detail error jika terjadi 500
                console.error('Server Error:', response.status, response.statusText);
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            kategoriTableBody.innerHTML = data.kategori_html;
            kategoriPagination.innerHTML = data.kategori_pagination;
            ssdTableBody.innerHTML = data.ssd_html;
            ssdPagination.innerHTML = data.ssd_pagination;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
    }

    if (searchKategoriInput) {
        searchKategoriInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchData, doneTypingInterval);
        });
    }

    if (searchSsdInput) {
        searchSsdInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchData, doneTypingInterval);
        });
    }
});
