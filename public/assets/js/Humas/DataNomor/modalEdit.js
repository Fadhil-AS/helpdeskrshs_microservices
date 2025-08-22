document.addEventListener('DOMContentLoaded', function() {
    var modalEdit = document.getElementById('modalEditNomor');

    modalEdit.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        var nomorLama = button.getAttribute('data-nomor');
        var actionUrl = button.getAttribute('data-action');
        var field = button.getAttribute('data-field');

        var form = modalEdit.querySelector('#editNomorForm');
        form.setAttribute('action', actionUrl);

        modalEdit.querySelector('#modalEditNomorLabel').textContent = "Ubah " + nama;
        modalEdit.querySelector('#edit_id').value = id;
        modalEdit.querySelector('#edit_field').value = field;
        modalEdit.querySelector('#nomor_lama_text').textContent = nomorLama || 'Belum diatur';
        modalEdit.querySelector('#edit_nomor_baru').value = '';
    });

    const inputNomorBaru = document.getElementById('edit_nomor_baru');

    if (inputNomorBaru) {
        inputNomorBaru.addEventListener('input', function(event) {
            const sanitizedValue = event.target.value.replace(/[^0-9]/g, '');
            event.target.value = sanitizedValue;
        });
    }
});
