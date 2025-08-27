document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('inputTiket');
    const searchContainer = document.getElementById('search-container');
    const btnCariTiket = document.getElementById('btnCariTiket');
    const hasilArea = document.getElementById('hasilArea');
    const globalMessages = document.getElementById('globalMessages');
    console.log("lacak.js: Elemen dasar telah dipilih.");

    const phoneModalEl = document.getElementById('phoneModal');
    const ticketListModalEl = document.getElementById('ticketListModal');
    const paginationContainer = document.getElementById('paginationContainer');
    const laporanListContainer = document.getElementById('laporanListContainer');

    let currentNameForPagination = '';
    let currentPhoneForPagination = '';
    let countdownInterval;


    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenMeta) {
        return;
    }
    const csrfToken = csrfTokenMeta.getAttribute('content');
    const searchUrl = searchContainer?.dataset.searchUrl;
    const byNameUrl = searchContainer?.dataset.byNameUrl;
    console.log("lacak.js: URL dikonfigurasi. Search URL:", searchUrl);

    async function cariTiket() {
        const query = searchInput.value.trim();
        if (!query) {
            displayGlobalMessage('Input pencarian tidak boleh kosong.', 'warning');
            return;
        }
        showLoading();

        try {
            console.log("lacak.js: Mengirim request ke server...");
            const response = await fetch(searchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ searchInput: query })
            });

            const result = await response.json();
            console.log("lacak.js: Menerima respons dari server:", result);

            if (!response.ok) {
                throw new Error(result.message || `Error ${response.status}: Terjadi kesalahan.`);
            }

            if (!result.success) {
                throw new Error(result.message || 'Tiket tidak ditemukan.');
            }

            if (result.action === 'request_phone') {
                handlePhoneRequest(result.name);
            } else {
                renderTicketResult(result);
            }

        } catch (error) {
            console.error("lacak.js: Terjadi error di dalam cariTiket:", error);
            showError(error.message);
        }
    }

    function handlePhoneRequest(name) {
        const phoneModal = bootstrap.Modal.getOrCreateInstance(phoneModalEl);
        phoneModalEl.querySelector('#searchNameDisplay').textContent = name;
        phoneModalEl.dataset.searchName = name;
        phoneModal.show();
        hasilArea.innerHTML = '';
    }

    async function fetchAndDisplayTickets(pageUrl) {
        const submitBtn = phoneModalEl?.querySelector('#btnVerifyPhone');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari...';
        }
        laporanListContainer.innerHTML = '<div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div><p>Memuat...</p></div>';

        try {
            const response = await fetch(pageUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ name: currentNameForPagination, phone: currentPhoneForPagination })
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal mengambil data laporan.');
            }

            if (!result.success) {
                if (response.status === 404) {
                    const phoneModal = bootstrap.Modal.getInstance(phoneModalEl);
                    if (phoneModal) phoneModal.hide();
                    showError(result.message);
                    return;
                }
                throw new Error(result.message);
            }

            renderTicketList(result.laporan.data);
            // --- Mengirim seluruh objek paginasi, bukan hanya links ---
            renderPagination(result.laporan);

            const phoneModal = bootstrap.Modal.getInstance(phoneModalEl);
            if (phoneModal) phoneModal.hide();

            const ticketListModal = bootstrap.Modal.getOrCreateInstance(ticketListModalEl);
            ticketListModal.show();

        } catch (error) {
            const errorDiv = phoneModalEl?.querySelector('#phoneError');
            if (errorDiv) {
                errorDiv.textContent = error.message;
                errorDiv.classList.remove('d-none');
            } else {
                laporanListContainer.innerHTML = `<p class="text-danger text-center">${error.message}</p>`;
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Verifikasi';
            }
        }
    }

    function renderTicketList(laporanList) {
        laporanListContainer.innerHTML = '';
        if (!laporanList || laporanList.length === 0) {
            laporanListContainer.innerHTML = '<p class="text-center text-muted p-3">Tidak ada laporan yang ditemukan.</p>';
        } else {
            laporanList.forEach(laporan => {
                const statusClass = getStatusClass(laporan.status);
                const itemHtml = `
                    <a href="#" class="list-group-item list-group-item-action" data-ticket-id="${laporan.id}">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 fw-bold">${laporan.id}</h6>
                            <small class="text-muted">${laporan.tgl}</small>
                        </div>
                        <p class="mb-1">${laporan.judul}</p>
                        <span class="status-badge ${statusClass}">${laporan.status}</span>
                    </a>`;
                laporanListContainer.innerHTML += itemHtml;
            });
        }
    }

    function renderPagination(paginationData) {
        paginationContainer.innerHTML = '';
        const links = paginationData.links;
        if (!links || links.length <= 3) return;

        const currentPage = paginationData.current_page;
        const lastPage = paginationData.last_page;
        const firstPageUrl = paginationData.first_page_url;
        const lastPageUrl = paginationData.last_page_url;
        const prevPageUrl = paginationData.prev_page_url;
        const nextPageUrl = paginationData.next_page_url;

        // Tombol "Pertama" (<<)
        paginationContainer.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-url="${firstPageUrl}" aria-label="First">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;

        // Tombol "Sebelumnya" (<)
        paginationContainer.innerHTML += `
            <li class="page-item ${!prevPageUrl ? 'disabled' : ''}">
                <a class="page-link" href="#" data-url="${prevPageUrl}" aria-label="Previous">
                    <span aria-hidden="true">&lsaquo;</span>
                </a>
            </li>`;

        // Tombol Halaman Angka
        links.forEach(link => {
            if (!isNaN(link.label)) {
                const li = document.createElement('li');
                li.className = `page-item ${link.active ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#" data-url="${link.url}">${link.label}</a>`;
                paginationContainer.appendChild(li);
            }
        });

        // Tombol "Berikutnya" (>)
        paginationContainer.innerHTML += `
            <li class="page-item ${!nextPageUrl ? 'disabled' : ''}">
                <a class="page-link" href="#" data-url="${nextPageUrl}" aria-label="Next">
                    <span aria-hidden="true">&rsaquo;</span>
                </a>
            </li>`;

        // Tombol "Terakhir" (>>)
        paginationContainer.innerHTML += `
            <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                <a class="page-link" href="#" data-url="${lastPageUrl}" aria-label="Last">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
    }


    async function handlePhoneSubmit() {
        const name = phoneModalEl.dataset.searchName;
        const phoneInput = phoneModalEl.querySelector('#inputNomorHp');
        const phone = phoneInput.value.trim();
        const errorDiv = phoneModalEl.querySelector('#phoneError');

        if (!/^[0-9]{9,15}$/.test(phone)) {
            errorDiv.textContent = 'Nomor telepon tidak valid. Harap masukkan 9-15 digit angka.';
            errorDiv.classList.remove('d-none');
            return;
        }
        errorDiv.classList.add('d-none');

        currentNameForPagination = name;
        currentPhoneForPagination = phone;

        fetchAndDisplayTickets(byNameUrl);
    }

    function handleTicketSelection(ticketId) {
        const ticketListModal = bootstrap.Modal.getInstance(ticketListModalEl);
        if (ticketListModal) ticketListModal.hide();
        searchInput.value = ticketId;
        cariTiket();
    }

    function generateFileAttachmentHtml(files, title) {
        if (!files || files.length === 0) return '';
        const fileItems = files.map(filePath => {
            if (!filePath || filePath.trim() === '') return '';
            const trimmedPath = filePath.trim();
            const fileName = trimmedPath.split('/').pop();
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const publicUrl = `/storage/${trimmedPath}`;
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            if (imageExtensions.includes(fileExtension)) {
                return `<div class="file-attachment-item-image"><a href="${publicUrl}" target="_blank" title="${fileName}"><img src="${publicUrl}" alt="${fileName}" class="timeline-image-preview"><span class="file-name">${fileName}</span></a></div>`;
            } else {
                let iconClass = 'bi bi-file-earmark-text text-secondary';
                if (fileExtension === 'pdf') iconClass = 'bi bi-file-earmark-pdf text-danger';
                else if (['doc', 'docx'].includes(fileExtension)) iconClass = 'bi bi-file-earmark-word text-info';
                return `<div class="file-attachment-item"><a href="${publicUrl}" target="_blank" title="${fileName}"><i class="${iconClass}"></i> ${fileName}</a></div>`;
            }
        }).join('');
        return `<div class="file-attachment-container mt-2"><small class="text-muted d-block mb-1">${title}:</small><div class="d-flex flex-wrap gap-3 align-items-center">${fileItems}</div></div>`;
    }

    function startCountdown(elementId, progressBarId, tglSelesaiInternalISO) {
        const countDownElement = document.getElementById(elementId);
        const progressBar = document.getElementById(progressBarId);
        if (!countDownElement || !progressBar) return;

        const batasWaktu = new Date(new Date(tglSelesaiInternalISO).getTime() + 72 * 60 * 60 * 1000);
        const totalDurasiMs = 72 * 60 * 60 * 1000;

        if (countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            const sisaMs = batasWaktu - new Date();
            if (sisaMs < 0) {
                clearInterval(countdownInterval);
                countDownElement.innerHTML = "Waktu habis";
                progressBar.style.width = '0%';
                return;
            }
            const persenTersisa = (sisaMs / totalDurasiMs) * 100;
            progressBar.style.width = persenTersisa + '%';
            const jam = Math.floor(sisaMs / 3600000);
            const menit = Math.floor((sisaMs % 3600000) / 60000);
            const detik = Math.floor((sisaMs % 60000) / 1000);
            countDownElement.innerHTML = `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
        }, 1000);
    }

    function renderTicketResult(result) {
        if (!result.success || !result.tiket) {
            showError(result.message || 'Pastikan input yang Anda masukkan benar.');
            return;
        }

        const tiket = result.tiket;
        const files = result.files;

        const filePengaduanAwalHtml = generateFileAttachmentHtml(files.pengaduan, 'File Pengaduan Awal');
        const initialTimelineEntryHtml = `<div class="timeline-item"><div class="fw-bold">Pelapor <span class="text-muted small fw-normal">${tiket.tanggal_complaint_timelineFormat || ''}</span></div><div class="timeline-title">Tiket Dibuat</div><div>Tiket <b> ${tiket.id_complaint || ''} </b> telah dibuat.</div>${filePengaduanAwalHtml}</div>`;

        const additionalRiwayatHtml = (result.riwayat_penanganan || []).map(item => {
            let fileRiwayatHtml = '';
            const judulAksiLower = (item.judul_aksi || '').toLowerCase();
            if (judulAksiLower.includes('klarifikasi')) {
                fileRiwayatHtml = generateFileAttachmentHtml(files.klarifikasi, 'File Bukti Klarifikasi');
            } else if (judulAksiLower.includes('tindak lanjuti')) {
                fileRiwayatHtml = generateFileAttachmentHtml(files.tindak_lanjut, 'File Tindak Lanjut');
            }
            return `<div class="timeline-item"><div class="fw-bold">${item.aktor || ''} <span class="text-muted small fw-normal">${item.tanggal_aksi || ''}</span></div><div class="timeline-title">${item.judul_aksi || ''}</div><div>${item.deskripsi_aksi || ''}</div>${fileRiwayatHtml}</div>`;
        }).join('');

        const fullRiwayatHtml = initialTimelineEntryHtml + additionalRiwayatHtml;
        const timelineSectionHtml = `<hr class="my-3"><h5 class="fw-bold">Riwayat Penanganan</h5><p class="text-muted mb-2">Perkembangan penanganan tiket Anda</p><div class="timeline">${fullRiwayatHtml}</div>`;

        let detailTambahanHtml = '';
        if (tiket.is_menunggu_konfirmasi) {
            detailTambahanHtml = `<div class="alert alert-warning mt-4 mb-4" id="konfirmasiArea-${tiket.id_complaint}"><div class="d-flex align-items-center mb-2"><i class="bi bi-clock me-2 fs-4"></i><strong class="me-auto">Waktu Konfirmasi Tersisa</strong></div><div class="progress mb-2" style="height: 10px;"><div class="progress-bar bg-primary" id="progressBar-${tiket.id_complaint}" role="progressbar" style="width: ${tiket.persen_waktu_konfirmasi || '100%'};"></div></div><p>Anda memiliki waktu <strong id="sisaWaktu-${tiket.id_complaint}">${tiket.waktu_konfirmasi_tersisa || 'Memuat...'}</strong> untuk mengkonfirmasi. Jika tidak, tiket akan otomatis ditutup.</p><div class="d-flex gap-2 flex-wrap"><button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-id-complaint="${tiket.id_complaint}"><i class="bi bi-check-circle pe-1"></i> Masalah Terselesaikan</button><button class="btn btn-danger btn-md" data-bs-toggle="modal" data-bs-target="#belumSelesaiModal" data-id-complaint="${tiket.id_complaint}"><i class="bi bi-x-circle pe-1"></i> Masalah Belum Terselesaikan</button></div></div>`;
            if (tiket.waktu_konfirmasi_tersisa !== "Waktu habis" && tiket.tgl_selesai_internal) {
                setTimeout(() => startCountdown(`sisaWaktu-${tiket.id_complaint}`, `progressBar-${tiket.id_complaint}`, tiket.tgl_selesai_internal), 100);
            }
        } else if (tiket.sudah_memberi_feedback) {
            detailTambahanHtml = `<div class="alert alert-success mt-4 mb-4"><div class="d-flex align-items-center"><i class="bi bi-star-fill me-2 fs-4"></i><strong class="me-auto">Feedback Diterima</strong></div><p class="mb-0 mt-2">Terima kasih atas penilaian dan masukan yang Anda berikan.</p></div>`;
        }

        const statusBadgeClass = getStatusClass(tiket.status);

        // let statusBadgeClass = 'bg-secondary';
        // if (['On Progress', 'Dalam Proses'].includes(tiket.status)) statusBadgeClass = 'bg-primary';
        // else if (['Menunggu Konfirmasi Pelapor', 'Menunggu Konfirmasi'].includes(tiket.status)) statusBadgeClass = 'bg-warning text-dark';
        // else if (['Close', 'Selesai'].includes(tiket.status)) statusBadgeClass = 'bg-success';
        // else if (['Open', 'Baru', 'Banding'].includes(tiket.status)) statusBadgeClass = 'btn-simpan text-white';

        hasilArea.innerHTML = `<div class="container border rounded p-3 p-md-4 mt-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="fs-5 text-break">Tiket: ${tiket.id_complaint}</strong>
                <span class="status-badge ${statusBadgeClass} text-nowrap">${tiket.status}</span>
            </div>
            ${detailTambahanHtml}
            <div class="row mb-3">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="info-label">Tanggal Dibuat:</div>
                    <div class="fw-bold">${tiket.tanggal_dibuat || ''}</div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Tanggal Diperbarui:</div>
                    <div class="fw-bold">${tiket.tanggal_diperbarui || ''}</div>
                </div>
            </div>
            <hr class="my-3">
            <div class="mb-3">
                <div class="info-label">Ditangani Oleh:</div>
                <div class="fw-bold">${tiket.ditangani_oleh || ''}</div>
            </div>

            <hr class="my-3">
            <div class="mb-3">
                <div class="info-label">Isi Pengaduan:</div>
                <div class="isi-pengaduan" style="white-space: pre-wrap;">${tiket.isi_complaint || 'Tidak ada deskripsi.'}</div>
            </div>
            <hr class="my-3">
            <div class="mb-3">
                <div class="info-label">Deskripsi Status Terkini:</div>
                <div>${tiket.deskripsi_status_terkini || ''}</div>
            </div>
            ${timelineSectionHtml}
        </div>`;
    }


    function showLoading() {
        hasilArea.innerHTML = `<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Mencari tiket...</p></div>`;
    }
    function showError(message) {
        hasilArea.innerHTML = `<div class="no-data"><i class="bi bi-wifi-off"></i><div class="text-bold mt-2">Gagal Memuat Data</div><div>${message}. Silakan coba lagi.</div></div>`;
    }
    function displayGlobalMessage(message, type = 'info') {
        if (globalMessages) {
            globalMessages.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
        }
    }
    function getStatusClass(status) {
        if (!status) return 'status-open';
        const s = status.toLowerCase().trim();

        if (s.includes('open') || s.includes('baru')) {
            return 'status-open';
        } else if (s.includes('progress') || s.includes('proses')) {
            return 'status-on-progress';
        } else if (s.includes('menunggu konfirmasi')) {
            return 'status-menunggu-konfirmasi';
        } else if (s.includes('close') || s.includes('selesai')) {
            return 'status-close';
        } else if (s.includes('banding')) {
            return 'status-banding';
        }

        return 'status-open';
    }

    if (btnCariTiket) {
        btnCariTiket.addEventListener('click', cariTiket);
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                cariTiket();
            }
        });

    }

    const inputNomorHp = document.getElementById('inputNomorHp');
    if (inputNomorHp) {
        inputNomorHp.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }

    document.getElementById('btnVerifyPhone')?.addEventListener('click', handlePhoneSubmit);
    document.getElementById('laporanListContainer')?.addEventListener('click', function (event) {
        const target = event.target.closest('.list-group-item-action');
        if (target) {
            event.preventDefault();
            handleTicketSelection(target.dataset.ticketId);
        }
    });

    paginationContainer?.addEventListener('click', function (event) {
        event.preventDefault();
        const target = event.target.closest('a.page-link');
        const url = target?.dataset.url;
        if (url && url !== 'null') {
            fetchAndDisplayTickets(url);
        }
    });

    if (searchInput && searchInput.value.trim() !== '') {
        cariTiket();
    }

    const btnBuatTiketBaru = document.getElementById('btnBuatTiketBaruDariModal');
    if (btnBuatTiketBaru) {
        btnBuatTiketBaru.addEventListener('click', async function () {
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
        feedbackModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const ticketId = button.getAttribute('data-id');
            this.querySelector('#btnSubmitFeedback').setAttribute('data-id', ticketId);
        });
    }

    function displayGlobalMessage(message, type = 'info') {
        if (globalMessages) {
            globalMessages.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => { globalMessages.innerHTML = ''; }, 4000);
        }
    }
});
