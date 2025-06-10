document.addEventListener('DOMContentLoaded', function () {
    // Kita targetkan container dari tabel klasifikasi agar lebih spesifik
    // Anda mungkin perlu menambahkan id="tabel-klasifikasi-container" pada div yang membungkus tabel ini di Blade
    const container = document.querySelector('#tabel-klasifikasi-container');

    // Jika container tidak ditemukan di halaman ini, hentikan script
    if (!container) return;

    container.addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (!target) return;

        // Hanya jalankan jika tombol Simpan di dalam container ini yang diklik
        if (target.classList.contains('btn-inline-save')) {
            event.preventDefault();

            // PENTING: Hentikan listener lain (dari fungsiTabel.js) agar tidak ikut berjalan
            event.stopImmediatePropagation();

            const row = target.closest('tr');
            const url = target.dataset.url;
            const inputField = row.querySelector('.editable-input');
            const dataKey = inputField.name;
            const newValue = inputField.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (newValue.trim() === "") {
                alert("Kolom tidak boleh kosong.");
                return;
            }

            const bodyData = {};
            bodyData[dataKey] = newValue;

            // Kirim data ke server
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
                // Tampilkan pesan dari server
                if(data.message) {
                    alert(data.message);
                }
                // Langsung reload halaman, baik sukses maupun gagal
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi ke server.');
                // Reload halaman agar pengguna melihat data yang konsisten
                location.reload();
            });
        }
    });
});
