document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('inputTiket');
    const hasilArea = document.getElementById('hasilArea');
    const globalMessages = document.getElementById('globalMessages');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function cariTiket() {
        const query = searchInput.value.trim();
        if (!query) {
            displayGlobalMessage('Input pencarian tidak boleh kosong.', 'warning');
            return;
        }

        hasilArea.innerHTML = `<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`;

        try {
            const response = await fetch("{{ route('ticketing.lacak.search') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ searchInput: query })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || `Error ${response.status}: Terjadi kesalahan.`);
           }

            if (!result.success) {
                throw new Error(result.message || 'Tiket tidak ditemukan.');
            }
            renderTicketResult(result.tiket);

        } catch (error) {
            hasilArea.innerHTML = `<div class="no-data"><i class="bi bi-x-circle"></i><div class="text-bold mt-2">Error</div><div>${error.message}</div></div>`;
        }
    }

    document.querySelector('.btn-simpan[onclick="cariTiket()"]').addEventListener('click', cariTiket);
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            cariTiket();
        }
    });

    function renderTicketResult(tiket) {
        let actionButtonsHtml = '';
        if (tiket.is_menunggu_konfirmasi) {
            actionButtonsHtml = `
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <h6 class="card-title fw-bold">Bagaimana Status Laporan Anda?</h6>
                        <p class="card-text text-muted">Mohon berikan konfirmasi Anda terhadap penyelesaian yang telah kami lakukan.</p>

                        <button class="btn btn-success me-2" id="btn-konfirmasi-selesai" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-id="${tiket.id_complaint}">
                            <i class="bi bi-check-circle"></i> Masalah Sudah Selesai
                        </button>
                        <button class="btn btn-warning" id="btn-konfirmasi-belum-selesai" data-bs-toggle="modal" data-bs-target="#belumSelesaiModal" data-id="${tiket.id_complaint}">
                            <i class="bi bi-exclamation-triangle"></i> Masalah Belum Selesai
                        </button>
                    </div>
                </div>
            `;
        }

        const ticketHtml = `
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">Tiket: ${tiket.id_complaint}</h5>
                    <span class="badge bg-info text-dark">${tiket.status}</span>
                </div>
                <div class="card-body">
                    <p><strong>Dibuat pada:</strong> ${tiket.tanggal_dibuat}</p>
                    <p><strong>Diperbarui pada:</strong> ${tiket.tanggal_diperbarui}</p>
                    <p><strong>Ditangani oleh:</strong> ${tiket.ditangani_oleh}</p>
                    <hr>
                    <p>${tiket.deskripsi_status_terkini}</p>
                </div>
            </div>
            ${actionButtonsHtml}
        `;
        hasilArea.innerHTML = ticketHtml;
    }

    const btnBuatTiketBaru = document.getElementById('btnBuatTiketBaruDariModal');
    if (btnBuatTiketBaru) {
        btnBuatTiketBaru.addEventListener('click', async function() {
            const idComplaint = this.dataset.id;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

            const url = `/ticketing/lacak-ticketing/tanggapi/${idComplaint}`;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ tanggapan: 'belum_selesai' })
                });
                const result = await response.json();

                if (!result.success) { throw new Error(result.message); }

                bootstrap.Modal.getInstance(document.getElementById('belumSelesaiModal')).hide();

                displayGlobalMessage(result.message, 'info');
                if (result.redirect_url) {
                    setTimeout(() => { window.location.href = result.redirect_url; }, 2000);
                }

            } catch (error) {
                alert('Error: ' + error.message);
                this.disabled = false;
                this.innerHTML = 'Buat Tiket Baru';
            }
        });
    }

    const belumSelesaiModal = document.getElementById('belumSelesaiModal');
    if (belumSelesaiModal) {
        belumSelesaiModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const ticketId = button.getAttribute('data-id');

            this.querySelector('#refTicketIdWarning').textContent = ticketId;
            this.querySelector('#refTicketIdText').textContent = ticketId;
            this.querySelector('#btnBuatTiketBaruDariModal').setAttribute('data-id', ticketId);
        });
    }

    const feedbackModal = document.getElementById('feedbackModal');
    if (feedbackModal) {
        feedbackModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const ticketId = button.getAttribute('data-id');
            this.querySelector('#btnSubmitFeedback').setAttribute('data-id', ticketId);
        });
    }

    function displayGlobalMessage(message, type = 'info') {
        if(globalMessages) {
            globalMessages.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => { globalMessages.innerHTML = ''; }, 4000);
        }
    }
});
