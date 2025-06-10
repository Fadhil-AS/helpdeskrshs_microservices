document.addEventListener('DOMContentLoaded', function () {
    const modalEdit = document.getElementById('modalEditUnitKerja');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        // Tombol yang di-klik untuk membuka modal
        const button = event.relatedTarget;

        // Ambil data JSON dari atribut data-unit
        const unitData = JSON.parse(button.getAttribute('data-unit'));

        // Cari form di dalam modal
        const form = modalEdit.querySelector('#formEditUnitKerja');

        // Bangun URL action untuk form update
        // Ganti 'humas.unit-kerja-humas' dengan nama route Anda jika berbeda
        const updateUrl = `/humas/unitKerjaHumas/${unitData.ID_BAGIAN}`;
        form.setAttribute('action', updateUrl);

        // Isi semua field di dalam modal dengan data
        modalEdit.querySelector('#edit-id-bagian-display').textContent = `ID: ${unitData.ID_BAGIAN}`;
        modalEdit.querySelector('#edit_nama_bagian').value = unitData.NAMA_BAGIAN;
        modalEdit.querySelector('#edit_nama_singular').value = unitData.NAMA_BAGIAN_SINGULAR;
        modalEdit.querySelector('#edit_nama_alternatif').value = unitData.NAMA_ALTERNATIF;
        modalEdit.querySelector('#edit_status').value = unitData.STATUS;
    });
});
