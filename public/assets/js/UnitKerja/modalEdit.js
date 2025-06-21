document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');

    if (!editModal || !editForm) {
        return;
    }

    const fileInput = document.getElementById('edit-file-bukti');
    const MAX_FILE_SIZE_MB = 2;
    const MAX_SIZE_IN_BYTES = MAX_FILE_SIZE_MB * 1024 * 1024;

    if (fileInput) {
        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                if (file.size > MAX_SIZE_IN_BYTES) {
                    alert(`Ukuran file terlalu besar! Maksimal adalah ${MAX_FILE_SIZE_MB} MB.`);

                    event.target.value = '';
                }
            }
        });
    }

    editModal.addEventListener('show.bs.modal', async function (event) {
        const button = event.relatedTarget;
        const complaintId = button.getAttribute('data-id');

        const urlTemplate = editModal.dataset.urlTemplate;
        editForm.action = urlTemplate.replace('PLACEHOLDER', complaintId);

        try {
            const response = await fetch(`/unitKerja/dashboard/detail/${complaintId}`);
            if (!response.ok) {
                throw new Error('Gagal mengambil data untuk diedit.');
            }
            const data = await response.json();
            populateEditForm(data);

        } catch (error) {
            console.error('Error saat mempersiapkan modal edit:', error);
            const modalInstance = bootstrap.Modal.getInstance(editModal);
            modalInstance.hide();
        }
    });

    function populateEditForm(data) {
        const toInputDate = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        document.getElementById('edit-id').textContent = data.ID_COMPLAINT;
        document.getElementById('edit-status-badge').innerHTML = getStatusBadge(data.STATUS);

        document.getElementById('edit-judul').value = data.JUDUL_COMPLAINT || '';
        document.getElementById('edit-deskripsi').value = data.ISI_COMPLAINT || '';
        document.getElementById('edit-permasalahan').value = data.PERMASALAHAN || '';
        document.getElementById('edit-petugas-evaluasi').value = data.PETUGAS_EVALUASI || 'Admin Unit Kerja';

        document.getElementById('edit-tanggal-evaluasi').value = toInputDate(data.TGL_EVALUASI);

        document.getElementById('edit-klarifikasi-unit').value = data.EVALUASI_COMPLAINT || '';
        document.getElementById('edit-file-bukti').value = '';

        const tanggalEvaluasiInput = document.getElementById('edit-tanggal-evaluasi');

        if (data.TGL_PENUGASAN) {
            tanggalEvaluasiInput.disabled = false;
            tanggalEvaluasiInput.classList.remove('bg-light');
            tanggalEvaluasiInput.placeholder = '';

            const minDate = toInputDate(data.TGL_PENUGASAN);
            tanggalEvaluasiInput.min = minDate;

            const maxDate = toInputDate(new Date());
            tanggalEvaluasiInput.max = maxDate;

            tanggalEvaluasiInput.value = toInputDate(data.TGL_EVALUASI);

        } else {
            tanggalEvaluasiInput.disabled = true;
            tanggalEvaluasiInput.classList.add('bg-light');
            tanggalEvaluasiInput.value = '';
            tanggalEvaluasiInput.removeAttribute('min');
            tanggalEvaluasiInput.removeAttribute('max');
            tanggalEvaluasiInput.placeholder = 'Menunggu penugasan Humas';
        }
    }

    function formatDate(dateString) {
        if (!dateString) return 'Belum dievaluasi';
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function getStatusBadge(status) {
        if (!status) return `<span class="badge bg-secondary">N/A</span>`;
        const statusText = status;
        let badgeClass = 'bg-secondary';
        switch (status) {
            case 'Open': badgeClass = 'bg-success'; break;
            case 'On Progress': badgeClass = 'bg-warning'; break;
            case 'Menunggu Konfirmasi': badgeClass = 'bg-info'; break;
            case 'Close': badgeClass = 'bg-danger'; break;
            case 'Banding': badgeClass = 'bg-danger'; break;
        }
        return `<span class="badge ${badgeClass}">${statusText}</span>`;
    }
})
