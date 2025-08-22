document.addEventListener('DOMContentLoaded', function() {
    var modalEditKategori = document.getElementById('modalEditKategori');
    if(modalEditKategori) {
        modalEditKategori.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var namaKategori = button.getAttribute('data-kategori');
            var form = document.getElementById('editKategoriForm');
            var inputNama = document.getElementById('edit_kategori_nama');

            var url = window.ssdPageData.kategoriUpdateUrl;
            form.action = url.replace(':id', id);

            inputNama.value = namaKategori;
        });
    }

    var modalEditSsd = document.getElementById('modalEditSSD');
    if(modalEditSsd) {
        modalEditSsd.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var pertanyaan = button.getAttribute('data-pertanyaan');
            var jawaban = button.getAttribute('data-jawaban');
            var kategoriId = button.getAttribute('data-kategori-id');
            var form = document.getElementById('editSSDForm');
            var inputPertanyaan = document.getElementById('edit_pertanyaan');
            var inputJawaban = document.getElementById('edit_jawaban');
            var selectKategori = document.getElementById('edit_kategori_id');

            // GUNAKAN VARIABEL DARI window.ssdPageData
            var url = window.ssdPageData.ssdUpdateUrl;
            form.action = url.replace(':id', id);

            inputPertanyaan.value = pertanyaan;
            inputJawaban.value = jawaban;
            selectKategori.value = kategoriId;
        });
    }
});
