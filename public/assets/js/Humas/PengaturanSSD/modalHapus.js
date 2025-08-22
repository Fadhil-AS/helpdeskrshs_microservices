document.addEventListener('DOMContentLoaded', function () {
    var hapusModal = document.getElementById('hapusModal');
    hapusModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;

        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');

        var form = hapusModal.querySelector('#deleteDireksiForm');
        var actionUrl = "/humas/DireksiHumas/" + id;
        form.setAttribute('action', actionUrl);

        var namaElement = hapusModal.querySelector('#namaDireksiHapus');
        namaElement.textContent = nama;
    });
});
