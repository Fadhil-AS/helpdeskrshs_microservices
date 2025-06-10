document.addEventListener('DOMContentLoaded', function () {
    const modalEditAdmin = document.getElementById('modalEditAdmin');
    if (modalEditAdmin) {
        const formEdit = document.getElementById('formEditAdmin');
        const modalInstance = new bootstrap.Modal(modalEditAdmin);

        modalEditAdmin.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const adminData = JSON.parse(button.getAttribute('data-admin'));

            const updateUrl = `/humas/userComplaint/${adminData.NO_REGISTER}`;
            formEdit.setAttribute('action', updateUrl);

            modalEditAdmin.querySelector('#edit_no_register').textContent = adminData.NO_REGISTER;
            formEdit.querySelector('#edit_username').value = adminData.USERNAME;
            formEdit.querySelector('#edit_name').value = adminData.NAME;
            formEdit.querySelector('#edit_nip').value = adminData.NIP;
            formEdit.querySelector('#edit_no_tlpn').value = adminData.NO_TLPN;
            formEdit.querySelector('#edit_id_bagian').value = adminData.ID_BAGIAN;
            formEdit.querySelector('#edit_validasi').value = adminData.VALIDASI;
        });

        formEdit.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(formEdit);

            fetch(formEdit.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': formEdit.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    alert('Validasi gagal! Cek kembali data Anda.');
                } else if (data.success) {
                    modalInstance.hide();
                    alert(data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui data.');
            });
        });
    }
});
