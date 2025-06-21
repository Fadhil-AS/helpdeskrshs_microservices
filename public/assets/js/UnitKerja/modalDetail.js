document.addEventListener('DOMContentLoaded', function () {
    const detailModalElement = document.getElementById('detailModal');
    const editModalElement = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');

    if (!detailModalElement || !editModalElement || !editForm) {
        console.error("Satu atau lebih elemen penting (detailModal, editModal, editForm) tidak ditemukan di HTML Anda.");
        return;
    }

    const detailModal = new bootstrap.Modal(detailModalElement);
    const editModal = new bootstrap.Modal(editModalElement);

    let complaintIdForEdit = null;

    detailModalElement.addEventListener('show.bs.modal', async function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        const complaintId = button.getAttribute('data-id');

        resetModalFields();

        try {
            const urlTemplate = detailModalElement.dataset.urlTemplate;
            const finalUrl = urlTemplate.replace('PLACEHOLDER', complaintId);
            const response = await fetch(finalUrl);
            if (!response.ok) throw new Error('Data pengaduan tidak ditemukan.');
            const data = await response.json();

            populateDetailFields(data);
        } catch (error) {
            console.error('Error fetching detail:', error);
            const body = detailModalElement.querySelector('.modal-body');
            if (body) body.innerHTML = `<p class="text-center text-danger p-5">${error.message}</p>`;
        }
    });

    detailModalElement.addEventListener('click', function(event) {
        const editButton = event.target.closest('.btn-edit');
        if (editButton) {
            const complaintId = editButton.getAttribute('data-id');
            if (complaintId) {
                complaintIdForEdit = complaintId;
                detailModal.hide();
            }
        }
    });

    detailModalElement.addEventListener('hidden.bs.modal', function () {
        if (complaintIdForEdit) {
            editModal.show();
        }
    });


    editModalElement.addEventListener('show.bs.modal', async function () {
        if (!complaintIdForEdit) return;

        const complaintId = complaintIdForEdit;

        const urlTemplate = editModalElement.dataset.urlTemplate;
        editForm.action = urlTemplate.replace('PLACEHOLDER', complaintId);

        try {
            const response = await fetch(`/unitKerja/dashboard/detail/${complaintId}`);
            if (!response.ok) throw new Error('Gagal mengambil data untuk diedit.');
            const data = await response.json();
            populateEditForm(data);
        } catch (error) {
            console.error('Error saat mempersiapkan modal edit:', error);
            editModal.hide();
        }
    });

    editModalElement.addEventListener('hidden.bs.modal', function() {
        complaintIdForEdit = null;
    });

    function populateDetailFields(data) {
        const formatDate = (d) => d ? new Date(d).toLocaleString('id-ID', { dateStyle: 'long'}) : '-';
        const text = (val) => val || '-';

        document.getElementById('detail-id').textContent = data.ID_COMPLAINT;
        document.getElementById('detail-status-badge').innerHTML = getStatusBadge(data.STATUS);

        document.getElementById('detail-judul').textContent = text(data.JUDUL_COMPLAINT);
        document.getElementById('detail-tanggal-pengaduan').textContent = formatDate(data.TGL_COMPLAINT);
        document.getElementById('detail-no-tlpn').textContent = text(data.NO_TLPN);
        document.getElementById('detail-grading-badge').innerHTML = getGradingBadge(data.GRANDING);
        document.getElementById('detail-nama-pelapor').textContent = text(data.NAME);
        document.getElementById('detail-unit-kerja').textContent = text(data.unit_kerja?.NAMA_BAGIAN);
        document.getElementById('detail-no-medrec').textContent = text(data.NO_MEDREC);
        document.getElementById('detail-jenis-laporan').textContent = text(data.jenis_laporan?.JENIS_LAPORAN);
        document.getElementById('detail-media-pengaduan').textContent = text(data.jenis_media?.JENIS_MEDIA);
        document.getElementById('detail-petugas-pelapor').textContent = text(data.PETUGAS_PELAPOR);
        document.getElementById('detail-klasifikasi').textContent = text(data.klasifikasi_pengaduan?.KLASIFIKASI_PENGADUAN);
        document.getElementById('detail-deskripsi').innerHTML = text(data.ISI_COMPLAINT?.replace(/\n/g, '<br>'));
        document.getElementById('detail-permasalahan').innerHTML = text(data.PERMASALAHAN?.replace(/\n/g, '<br>'));

        document.getElementById('detail-petugas-evaluasi').textContent = text(data.PETUGAS_EVALUASI);
        document.getElementById('detail-tanggal-evaluasi').textContent = formatDate(data.TGL_EVALUASI);
        document.getElementById('detail-tanggal-selesai').textContent = formatDate(data.TGL_SELESAI);
        document.getElementById('detail-penyelesaian').textContent = text(data.penyelesaian_pengaduan?.PENYELESAIAN_PENGADUAN);
        document.getElementById('detail-klarifikasi-unit').value = text(data.TINDAK_LANJUT_HUMAS);
        document.getElementById('detail-tindak-lanjut-humas').value = text(data.EVALUASI_COMPLAINT);

        const fileListContainer = document.getElementById('detail-file-list');
        fileListContainer.innerHTML = '';
        // let displayedFileCount = 0;

        if (data.klarifikasi_files && data.klarifikasi_files.length > 0) {
            data.klarifikasi_files.forEach(filePath => {
                const trimmedPath = filePath.trim();
                if (trimmedPath === '') return;

                const publicUrl = `/storage/${trimmedPath}`;
                const fileName = trimmedPath.split('/').pop();
                const fileExtension = fileName.split('.').pop().toLowerCase();

                let previewHtml = '';
                const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

                if (imageExtensions.includes(fileExtension)) {
                    previewHtml = `<img src="${publicUrl}" alt="${fileName}" class="img-fluid rounded mb-2" style="height: 60px; width: 100%; object-fit: cover;">`;
                } else {
                    let iconClass = 'bi-file-earmark-text text-secondary';
                    if (fileExtension === 'pdf') iconClass = 'bi-file-earmark-pdf text-danger';
                    else if (['doc', 'docx'].includes(fileExtension)) iconClass = 'bi-file-earmark-word text-primary';
                    previewHtml = `<div class="text-center mb-2"><i class="bi ${iconClass} fs-1"></i></div>`;
                }

                fileListContainer.innerHTML += `
                    <a href="${publicUrl}" target="_blank" class="text-decoration-none border rounded p-2 d-flex flex-column justify-content-between" style="width: 120px; text-align: center;">
                        ${previewHtml}
                        <small class="d-block text-truncate" title="${fileName}">${fileName}</small>
                    </a>`;
            });
        } else {
            fileListContainer.innerHTML = '<p class="text-muted small">Tidak ada file bukti klarifikasi.</p>';
        }

        const editButtons = detailModalElement.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.setAttribute('data-id', data.ID_COMPLAINT);
        });
    }

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
        document.getElementById('edit-petugas-evaluasi').value = data.PETUGAS_PELAPOR || 'Admin Unit Kerja';

        document.getElementById('edit-tanggal-evaluasi').value = toInputDate(data.TGL_EVALUASI);

        document.getElementById('edit-klarifikasi-unit').value = data.EVALUASI_COMPLAINT || '';
        document.getElementById('edit-file-bukti').value = '';

        const tglEvaluasiInput = document.getElementById('edit-tanggal-evaluasi');
        const today = new Date();
        today.setHours(today.getHours() + 7);
        const maxDate = today.toISOString().split('T')[0];
        tglEvaluasiInput.max = maxDate;

        if (data.TGL_PENUGASAN) {
            tglEvaluasiInput.min = toInputDate(data.TGL_PENUGASAN);
        } else {
            tglEvaluasiInput.removeAttribute('min');
        }

        tglEvaluasiInput.value = data.TGL_EVALUASI ? toInputDate(data.TGL_EVALUASI) : maxDate;
    }

    function resetModalFields() {
        const fields = ['id', 'judul', 'petugas-evaluasi', 'tanggal-pengaduan', 'no-tlpn', 'nama-pelapor', 'unit-kerja', 'no-medrec', 'jenis-laporan', 'media-pengaduan', 'petugas-pelapor', 'klasifikasi', 'deskripsi', 'permasalahan', 'tanggal-evaluasi', 'tanggal-selesai', 'penyelesaian'];
        fields.forEach(field => {
            const el = document.getElementById(`detail-${field}`);
            if (el) el.textContent = '...';
        });
        document.getElementById('detail-status-badge').innerHTML = '';
        document.getElementById('detail-grading-badge').innerHTML = '';
        document.getElementById('detail-file-list').innerHTML = '';
        document.getElementById('detail-klarifikasi-unit').value = '';
        document.getElementById('detail-tindak-lanjut-humas').value = '';
    }

    const getStatusBadge = (status) => {
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
     };
    const getGradingBadge = (grading) => {
        if (!grading) {
            return `<span class="badge bg-light text-dark">Belum Dinilai</span>`;
        }

        const gradingText = grading;
        let badgeClass = 'bg-secondary';

        switch (grading) {
            case 'Hijau':
                badgeClass = 'bg-success';
                break;
            case 'Kuning':
                badgeClass = 'bg-warning';
                break;
            case 'Merah':
                badgeClass = 'bg-danger';
                break;
        }

        return `<span class="badge ${badgeClass}">${gradingText}</span>`;
     };
});
