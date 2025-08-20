document.addEventListener('DOMContentLoaded', function() {
    // --- Logika untuk Modal Hapus Kategori ---
    var modalHapusKategori = document.getElementById('hapusKategoriModal');
    if(modalHapusKategori) {
        modalHapusKategori.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var namaKategori = button.getAttribute('data-kategori');
            var form = document.getElementById('deleteKategoriForm');
            var namaSpan = document.getElementById('kategoriHapusNama');

            // GUNAKAN VARIABEL DARI window.ssdPageData
            var url = window.ssdPageData.kategoriDestroyUrl;
            form.action = url.replace(':id', id);

            namaSpan.textContent = namaKategori;
        });
    }

    // --- Logika untuk Modal Hapus SSD ---
    var modalHapusSsd = document.getElementById('hapusModal');
    if(modalHapusSsd) {
        modalHapusSsd.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var pertanyaan = button.getAttribute('data-pertanyaan');
            var form = document.getElementById('deleteSSDForm');
            var pertanyaanSpan = document.getElementById('pertanyaanHapus');

            // GUNAKAN VARIABEL DARI window.ssdPageData
            var url = window.ssdPageData.ssdDestroyUrl;
            form.action = url.replace(':id', id);

            pertanyaanSpan.textContent = `"${pertanyaan}"`;
        });
    }
});
