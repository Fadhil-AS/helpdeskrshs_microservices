document.addEventListener('DOMContentLoaded', function () {
    const modalDetailAdmin = document.getElementById('modalDetailAdmin');

    if (modalDetailAdmin) {
        modalDetailAdmin.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const adminData = JSON.parse(button.getAttribute('data-admin'));
            const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };

            modalDetailAdmin.querySelector('#detail_no_register').textContent = `No. Register: ${adminData.NO_REGISTER}`;
            modalDetailAdmin.querySelector('#detail_username').textContent = adminData.USERNAME;
            modalDetailAdmin.querySelector('#detail_name').textContent = adminData.NAME;
            modalDetailAdmin.querySelector('#detail_nip').textContent = adminData.NIP || '-';
            modalDetailAdmin.querySelector('#detail_no_tlpn').textContent = adminData.NO_TLPN || '-';
            modalDetailAdmin.querySelector('#detail_special_code').textContent = adminData.SPECIAL_CODE || '-';
            modalDetailAdmin.querySelector('#detail_unit_kerja').textContent = adminData.unit_kerja ? adminData.unit_kerja.NAMA_BAGIAN : 'N/A';
            modalDetailAdmin.querySelector('#detail_tgl_insrow').textContent = new Date(adminData.TGL_INSROW).toLocaleDateString('id-ID', dateOptions);

            const statusBadge = modalDetailAdmin.querySelector('#detail_status_badge');
            if (adminData.VALIDASI === 'Y') {
                statusBadge.textContent = 'Tervalidasi';
                statusBadge.className = 'badge bg-success';
            } else {
                statusBadge.textContent = 'Belum Tervalidasi';
                statusBadge.className = 'badge bg-warning';
            }
        });
    }
});
