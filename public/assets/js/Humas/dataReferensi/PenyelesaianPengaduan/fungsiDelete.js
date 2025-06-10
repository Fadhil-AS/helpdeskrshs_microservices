document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('#tabel-penyelesaian-pengaduan-container');

    if (!container) return;

    container.addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (!target) return;

        if (target.classList.contains('btn-inline-delete')) {
            event.preventDefault();

            event.stopImmediatePropagation();

            const row = target.closest('tr');
            const url = target.dataset.url;
            const namaItem = target.dataset.name;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!confirm(`Apakah Anda yakin ingin menghapus "${namaItem}"? Aksi ini tidak dapat dibatalkan.`)) {
                return;
            }

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.style.transition = 'opacity 0.5s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 500);
                } else {
                    alert(data.message || 'Gagal menghapus data.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Tidak dapat terhubung ke server.');
            });
        }
    });
});
