const searchInput = document.getElementById('searchInput');
const tableBody = document.getElementById('complaintTableBody');
const searchForm = document.getElementById('searchForm');

let typingTimer;
const doneTypingInterval = 100;

searchInput.addEventListener('keyup', () => {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(performSearch, doneTypingInterval);
});

searchForm.addEventListener('submit', (e) => {
    e.preventDefault();
    performSearch();
});
function performSearch() {
    const query = searchInput.value;
    const url = new URL(searchForm.action);
    url.searchParams.set('search', query);

    fetch(url)
        .then(response => response.text())
        .then(html => {

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.getElementById('complaintTableBody');

            if (newTbody) {
                tableBody.innerHTML = newTbody.innerHTML;
            }
        })
        .catch(error => console.error('Error fetching search results:', error));
}
