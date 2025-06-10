document.addEventListener('DOMContentLoaded', function () {
    // Ambil elemen modal edit
    var modalEditDireksi = document.getElementById('modalEditDireksi');

    // Tambahkan event listener saat modal akan ditampilkan
    modalEditDireksi.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal (ikon pensil)
        var button = event.relatedTarget;

        // Ambil data dari atribut data-*
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        var no_tlpn = button.getAttribute('data-no_tlpn');
        var ket = button.getAttribute('data-ket');

        // Cari form di dalam modal
        var form = modalEditDireksi.querySelector('#editDireksiForm');
        // Buat URL action untuk form
        var actionUrl = "/humas/DireksiHumas/" + id;
        form.setAttribute('action', actionUrl);

        // Masukkan data ke dalam elemen-elemen di modal
        modalEditDireksi.querySelector('#editDireksiIdLabel').textContent = 'ID: ' + id;
        modalEditDireksi.querySelector('#edit_nama').value = nama;
        modalEditDireksi.querySelector('#edit_no_tlpn').value = no_tlpn;
        modalEditDireksi.querySelector('#edit_ket').value = ket;
    });
});
