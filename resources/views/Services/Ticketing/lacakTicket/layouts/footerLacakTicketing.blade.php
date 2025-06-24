{{-- footerLacakTicketing.blade.php --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>

<script>
    let countdownInterval = null;
    let currentComplaintIdForModal = null;
    let feedbackModalInstance = null;
    let belumSelesaiModalInstance = null;

    function generateFileAttachmentHtml(files, title) {
        if (!files || files.length === 0) {
            return '';
        }

        const fileItems = files.map(filePath => {
            if (!filePath || filePath.trim() === '') return '';

            const trimmedPath = filePath.trim();
            const fileName = trimmedPath.split('/').pop();
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const publicUrl = `/storage/${trimmedPath}`;
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

            if (imageExtensions.includes(fileExtension)) {
                return `
                    <div class="file-attachment-item-image">
                        <a href="${publicUrl}" target="_blank" title="${fileName}">
                            <img src="${publicUrl}" alt="${fileName}" class="timeline-image-preview">
                            <span class="file-name">${fileName}</span>
                        </a>
                    </div>`;
            } else {
                let iconClass = 'bi bi-file-earmark-text text-secondary';
                if (fileExtension === 'pdf') {
                    iconClass = 'bi bi-file-earmark-pdf text-danger';
                } else if (['doc', 'docx'].includes(fileExtension)) {
                    iconClass = 'bi bi-file-earmark-word text-info';
                }
                return `
                    <div class="file-attachment-item">
                        <a href="${publicUrl}" target="_blank" title="${fileName}">
                            <i class="${iconClass}"></i> ${fileName}
                        </a>
                    </div>`;
            }
        }).join('');

        return `
            <div class="file-attachment-container mt-2">
                <small class="text-muted d-block mb-1">${title}:</small>
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    ${fileItems}
                </div>
            </div>`;
    }

    async function cariTiket() {
        const searchInput = document.getElementById('inputTiket');
        const hasilArea = document.getElementById('hasilArea');
        const spinnerButton = document.querySelector('.lacak-container button.btn-simpan[onclick="cariTiket()"]');

        if (!searchInput || !hasilArea) {
            console.error("cariTiket: Elemen #inputTiket atau #hasilArea tidak ditemukan.");
            if (hasilArea) hasilArea.innerHTML =
                `<div class="no-data text-danger">Error: Elemen penting halaman tidak ditemukan.</div>`;
            return;
        }
        const searchInputValue = searchInput.value.trim();

        if (searchInputValue === '') {
            hasilArea.innerHTML =
                `<div class="no-data"><i class="bi bi-file-earmark-text"></i><div class="text-bold mt-2">Input kosong</div><div>Masukkan no tiket/no telepon/nama/no medrec untuk melihat status laporan Anda</div></div>`;
            return;
        }

        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }

        hasilArea.innerHTML =
            `<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Mencari tiket...</p></div>`;
        if (spinnerButton) {
            spinnerButton.disabled = true;
            spinnerButton.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Melacak...`;
        }

        try {
            const response = await fetch('{{ route('ticketing.lacak.search') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    searchInput: searchInputValue
                })
            });

            if (!response.ok) {
                let errorText = `Error HTTP: ${response.status} ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorText = errorData.message || errorText;
                } catch (e) {
                    /* ignore */
                }
                console.error('Error HTTP dari searchTicket:', errorText);
                hasilArea.innerHTML =
                    `<div class="no-data"><i class="bi bi-wifi-off"></i><div class="text-bold mt-2">Gagal Memuat Data</div><div>${errorText}. Silakan coba lagi.</div></div>`;
                if (spinnerButton) {
                    spinnerButton.disabled = false;
                    spinnerButton.innerHTML = `<i class="bi bi-search"></i> Lacak`;
                }
                return;
            }

            let result;
            try {
                result = await response.json();
            } catch (e) {
                const responseText = await response.text();
                console.error('Gagal parse JSON dari searchTicket:', e, "\nResponse Text:", responseText);
                hasilArea.innerHTML =
                    `<div class="no-data"><i class="bi bi-exclamation-triangle-fill"></i><div class="text-bold mt-2">Format Respons Salah</div><div>Server memberikan respons tak terduga. Periksa console.</div></div>`;
                if (spinnerButton) {
                    spinnerButton.disabled = false;
                    spinnerButton.innerHTML = `<i class="bi bi-search"></i> Lacak`;
                }
                return;
            }

            if (spinnerButton) {
                spinnerButton.disabled = false;
                spinnerButton.innerHTML = `<i class="bi bi-search"></i> Lacak`;
            }

            if (result.success && result.tiket) {
                const tiket = result.tiket;
                const files = result.files;

                const filePengaduanAwalHtml = generateFileAttachmentHtml(files.pengaduan, 'File Pengaduan Awal');

                const initialTimelineEntryHtml =
                    `<div class="timeline-item">
                        <div class="fw-bold">Pelapor <span class="text-muted small fw-normal">${tiket.tanggal_complaint_timelineFormat || 'N/A'}</span></div>
                        <div class="timeline-title">Tiket Dibuat</div>
                        <div>Tiket <b> ${tiket.id_complaint || 'N/A'} </b> telah dibuat.</div>
                        ${filePengaduanAwalHtml}
                    </div>`;

                const additionalRiwayatHtml = (result.riwayat_penanganan && Array.isArray(result
                        .riwayat_penanganan)) ?
                    result.riwayat_penanganan.map(item => {
                        let fileRiwayatHtml = '';
                        const judulAksiLower = item.judul_aksi ? item.judul_aksi.toLowerCase() : '';

                        if (judulAksiLower.includes('klarifikasi')) {
                            fileRiwayatHtml = generateFileAttachmentHtml(files.klarifikasi,
                                'File Bukti Klarifikasi');
                        } else if (judulAksiLower.includes('tindak lanjuti')) {
                            fileRiwayatHtml = generateFileAttachmentHtml(files.tindak_lanjut,
                                'File Tindak Lanjut');
                        }

                        return `
                            <div class="timeline-item">
                                <div class="fw-bold">${item.aktor || 'N/A'} <span class="text-muted small fw-normal">${item.tanggal_aksi || 'N/A'}</span></div>
                                <div class="timeline-title">${item.judul_aksi || 'N/A'}</div>
                                <div>${item.deskripsi_aksi || ''}</div>
                                ${fileRiwayatHtml}
                            </div>`;
                    }).join('') : '';
                const fullRiwayatHtml = initialTimelineEntryHtml + additionalRiwayatHtml;
                const timelineSectionHtml =
                    `<hr class="my-3"><h5 class="fw-bold">Riwayat Penanganan</h5><p class="text-muted mb-2">Perkembangan penanganan tiket Anda</p><div class="timeline">${fullRiwayatHtml}</div>`;

                let detailTambahanHtml = '';
                if (tiket.is_menunggu_konfirmasi) {
                    detailTambahanHtml = `
                        <div class="alert alert-warning mt-4 mb-4" id="konfirmasiArea-${tiket.id_complaint}">
                            <div class="d-flex align-items-center mb-2"><i class="bi bi-clock me-2 fs-4"></i><strong class="me-auto">Waktu Konfirmasi Tersisa</strong></div>
                            <div class="progress mb-2" style="height: 10px;"><div class="progress-bar bg-primary" id="progressBar-${tiket.id_complaint}" role="progressbar" style="width: ${tiket.persen_waktu_konfirmasi || '100%'};" aria-valuenow="${(tiket.persen_waktu_konfirmasi || '100').replace('%','')}" aria-valuemin="0" aria-valuemax="100"></div></div>
                            <p>Anda memiliki waktu <strong id="sisaWaktu-${tiket.id_complaint}">${tiket.waktu_konfirmasi_tersisa || 'Memuat...'}</strong> untuk mengkonfirmasi penyelesaian tiket ini. Jika tidak ada konfirmasi, tiket akan otomatis ditutup.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-id-complaint="${tiket.id_complaint}"><i class="bi bi-check-circle pe-1"></i> Masalah Terselesaikan</button>
                                <button class="btn btn-danger btn-md" data-bs-toggle="modal" data-bs-target="#belumSelesaiModal" data-id-complaint="${tiket.id_complaint}"><i class="bi bi-x-circle pe-1"></i> Masalah Belum Terselesaikan</button>
                            </div>
                        </div>`;
                    if (tiket.waktu_konfirmasi_tersisa !== "Waktu habis" && tiket.tgl_selesai_internal) {
                        setTimeout(() => {
                            startCountdown(`sisaWaktu-${tiket.id_complaint}`,
                                `progressBar-${tiket.id_complaint}`, tiket.tgl_selesai_internal,
                                `konfirmasiArea-${tiket.id_complaint}`);
                        }, 100);
                    }
                }

                let statusBadgeClass = 'bg-success';
                if (tiket.status === 'On Progress' || tiket.status === 'Dalam Proses') statusBadgeClass =
                    'bg-primary';
                else if (tiket.status === 'Menunggu Konfirmasi Pelapor' || tiket.status === 'Menunggu Konfirmasi')
                    statusBadgeClass = 'bg-warning text-dark';
                else if (tiket.status === 'Close' || tiket.status === 'Selesai') statusBadgeClass = 'bg-success';
                else if (tiket.status === 'Open' || tiket.status === 'Baru' || tiket.status === 'Banding')
                    statusBadgeClass = 'btn-simpan text-white';

                hasilArea.innerHTML = `
                    <div class="container border rounded p-3 p-md-4 mt-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3"><strong class="fs-5 text-break">Tiket: ${tiket.id_complaint}</strong><span class="status-badge ${statusBadgeClass} text-nowrap">${tiket.status}</span></div>
                        ${detailTambahanHtml}
                        <div class="row mb-3"><div class="col-md-6 mb-2 mb-md-0"><div class="info-label">Tanggal Dibuat:</div><div class="fw-bold">${tiket.tanggal_dibuat || 'N/A'}</div></div><div class="col-md-6"><div class="info-label">Tanggal Diperbarui:</div><div class="fw-bold">${tiket.tanggal_diperbarui || 'N/A'}</div></div></div>
                        <hr class="my-3"><div class="mb-3"><div class="info-label">Ditangani Oleh:</div><div class="fw-bold">${tiket.ditangani_oleh || 'N/A'}</div></div>
                        <hr class="my-3"><div class="mb-3"><div class="info-label">Deskripsi Status Terkini:</div><div>${tiket.deskripsi_status_terkini || 'N/A'}</div></div>
                        ${timelineSectionHtml}
                    </div>`;
            } else {
                hasilArea.innerHTML =
                    `<div class="no-data"><i class="bi bi-emoji-frown"></i><div class="text-bold mt-2">Data Tidak Ditemukan</div><div>${result.message || 'Pastikan input yang Anda masukkan benar.'}</div></div>`;
            }
        } catch (error) {
            console.error('Error tidak terduga saat mencari tiket:', error);
            if (spinnerButton) {
                spinnerButton.disabled = false;
                spinnerButton.innerHTML = `<i class="bi bi-search"></i> Lacak`;
            }
            hasilArea.innerHTML =
                `<div class="no-data"><i class="bi bi-wifi-off"></i><div class="text-bold mt-2">Terjadi Kesalahan Teknis</div><div>Gagal menghubungi server. Silakan coba lagi nanti.</div></div>`;
        }
    }

    async function tanggapiTiket(idComplaint, jenisTanggapan, submitButtonRef = null) {
        let originalButtonHtml = '';
        if (submitButtonRef) {
            originalButtonHtml = submitButtonRef.innerHTML;
            submitButtonRef.disabled = true;
            submitButtonRef.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
        }

        try {
            const response = await fetch(`{{ url('/ticketing/lacak-ticketing/tanggapi') }}/${idComplaint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tanggapan: jenisTanggapan
                })
            });

            const reEnableButton = () => {
                if (submitButtonRef) {
                    submitButtonRef.disabled = false;
                    submitButtonRef.innerHTML = originalButtonHtml;
                }
            };

            if (!response.ok) {
                let errorText = `Error HTTP: ${response.status} ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorText = errorData.message || errorText;
                } catch (e) {
                    /* ignore */
                }
                console.error('Error HTTP dari tanggapiTiket:', errorText);
                alert(`Gagal memproses tanggapan: ${errorText}`);
                reEnableButton();
                return {
                    success: false,
                    message: errorText
                };
            }

            let result;
            try {
                result = await response.json();
            } catch (e) {
                const responseText = await response.text();
                console.error('Gagal parse JSON dari tanggapiTiket:', e, "\nResponse Text:", responseText);
                alert('Server memberikan respons yang tidak terduga. Periksa console.');
                reEnableButton();
                return {
                    success: false,
                    message: 'Respons server tidak valid.'
                };
            }

            if (result.success) {
                reEnableButton();
                return {
                    success: true,
                    message: result.message,
                    new_status: result.new_status
                };
            } else {
                alert(result.message || 'Gagal memproses tanggapan dari server.');
                reEnableButton();
                return {
                    success: false,
                    message: result.message
                };
            }
        } catch (error) {
            console.error('Error pada tanggapiTiket (catch utama):', error);
            alert('Terjadi kesalahan teknis (catch) saat memproses tanggapan Anda.');
            if (submitButtonRef) {
                submitButtonRef.disabled = false;
                submitButtonRef.innerHTML = originalButtonHtml;
            }
            return {
                success: false,
                message: error.message
            };
        }
    }

    async function kirimFeedbackDetail(idComplaint, ratingBintang, feedbackText) {
        try {
            const response = await fetch('{{ route('ticketing.simpan-feedback') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_complaint: idComplaint,
                    rating: ratingBintang,
                    feedback_text: feedbackText
                })
            });

            if (!response.ok) {
                const errorResult = await response.json().catch(() => ({
                    message: "Gagal mengirim feedback (HTTP error) dan gagal parse error JSON."
                }));
                console.error("Gagal kirimFeedbackDetail (server HTTP error):", response.status, errorResult
                    .message);
                alert(`Gagal mengirim feedback: ${errorResult.message || response.statusText}`);
                return false;
            }
            const result = await response.json();
            if (result.success) {
                console.log("Feedback detail berhasil dikirim:", result.message);
                return true;
            } else {
                console.warn("Gagal kirimFeedbackDetail (result.success false):", result.message, result.errors);
                let errorMsg = result.message || "Gagal menyimpan feedback.";
                if (result.errors && typeof result.errors === 'object') {
                    errorMsg += "\nDetail:\n";
                    for (const field in result.errors) {
                        errorMsg += `- ${result.errors[field].join(', ')}\n`;
                    }
                }
                alert(errorMsg);
                return false;
            }
        } catch (error) {
            console.error('Error saat kirimFeedbackDetail (catch utama):', error);
            alert('Terjadi kesalahan teknis saat mengirimkan feedback tambahan. Periksa console dan koneksi Anda.');
            return false;
        }
    }

    function startCountdown(elementId, progressBarId, tglSelesaiInternalISO, konfirmasiAreaId) {
        const countDownElement = document.getElementById(elementId);
        const progressBar = document.getElementById(progressBarId);
        if (!countDownElement || !progressBar) return;

        const batasWaktu = new Date(new Date(tglSelesaiInternalISO).getTime() + 24 * 60 * 60 * 1000);
        const totalDurasiMs = 24 * 60 * 60 * 1000;

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

            const jam = Math.floor(sisaMs / (1000 * 60 * 60));
            const menit = Math.floor((sisaMs % (1000 * 60 * 60)) / (1000 * 60));
            const detik = Math.floor((sisaMs % (1000 * 60)) / 1000);
            countDownElement.innerHTML =
                `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
        }, 1000);
    }


    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM Siap. Bootstrap object:", typeof bootstrap, bootstrap ? "Tersedia" : "TIDAK TERSEDIA");

        const inputTiketEl = document.getElementById('inputTiket');
        if (inputTiketEl) {
            inputTiketEl.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    cariTiket();
                }
            });
        }


        const feedbackModalEl = document.getElementById('feedbackModal');
        if (feedbackModalEl) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                try {
                    feedbackModalInstance = new bootstrap.Modal(feedbackModalEl);
                } catch (e) {
                    console.error("Error membuat instance #feedbackModal:", e);
                }
            } else {
                console.error("bootstrap.Modal tidak ada untuk #feedbackModal.");
            }

            if (feedbackModalInstance) {
                feedbackModalEl.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    currentComplaintIdForModal = (button && button.matches(
                            '[data-bs-toggle="modal"]')) ? button.getAttribute('data-id-complaint') :
                        null;

                    const ratingContainer = feedbackModalEl.querySelector(
                        '#ratingContainer'); // UNTUK TOMBOL RATING
                    if (ratingContainer) {
                        ratingContainer.querySelectorAll('.rating-btn').forEach(btn => {
                            btn.classList.remove('btn-primary', 'text-white');
                            btn.classList.add('btn-outline-secondary');
                        });
                    }

                    const feedbackTextArea = feedbackModalEl.querySelector('textarea#feedbackText');
                    if (feedbackTextArea) feedbackTextArea.value = '';
                    const btnKirim = feedbackModalEl.querySelector('#btnSubmitFeedback');
                    if (btnKirim) {
                        btnKirim.dataset.ratingBintang = "0";
                        btnKirim.disabled = false;
                        btnKirim.innerHTML = "Kirim Feedback";
                    }
                });

                const ratingContainer = feedbackModalEl.querySelector('#ratingContainer');
                if (ratingContainer) {
                    ratingContainer.addEventListener('click', function(e) {
                        if (e.target.classList.contains('rating-btn')) {
                            const clickedButton = e.target;

                            ratingContainer.querySelectorAll('.rating-btn').forEach(btn => {
                                btn.classList.remove('active');
                            });

                            clickedButton.classList.add('active');

                            const ratingValue = clickedButton.textContent;
                            const btnKirim = feedbackModalEl.querySelector('#btnSubmitFeedback');
                            if (btnKirim) {
                                btnKirim.dataset.ratingBintang = ratingValue;
                            }
                        }
                    });
                }

                const btnKirimFeedback = feedbackModalEl.querySelector('#btnSubmitFeedback');
                if (btnKirimFeedback) {
                    const newBtnKirimFeedback = btnKirimFeedback.cloneNode(true);
                    btnKirimFeedback.parentNode.replaceChild(newBtnKirimFeedback, btnKirimFeedback);
                    newBtnKirimFeedback.addEventListener('click', async function() {
                        if (!currentComplaintIdForModal) {
                            alert("ID Tiket tidak ditemukan.");
                            return;
                        }
                        const thisButton = this;

                        let ratingBintang = parseInt(thisButton.dataset.ratingBintang) || 0;

                        if (ratingBintang < 1 || ratingBintang > 5) {
                            alert("Mohon pilih penilaian bintang (1-5).");
                            return;
                        }

                        thisButton.disabled = true;
                        thisButton.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...`;

                        const tanggapanResult = await tanggapiTiket(currentComplaintIdForModal,
                            'selesai');

                        if (tanggapanResult && tanggapanResult.success) {
                            alert(tanggapanResult.message);
                            const feedbackTextArea = feedbackModalEl.querySelector(
                                'textarea#feedbackText');
                            const feedbackText = feedbackTextArea ? feedbackTextArea.value.trim() :
                                '';

                            await kirimFeedbackDetail(currentComplaintIdForModal, ratingBintang,
                                feedbackText);

                            if (feedbackModalInstance) feedbackModalInstance.hide();
                            await cariTiket();
                        }

                        if (!(tanggapanResult && tanggapanResult.success)) {
                            thisButton.disabled = false;
                            thisButton.innerHTML = "Kirim Feedback";
                        }
                    });
                }
            }
        }

        const belumSelesaiModalEl = document.getElementById('belumSelesaiModal');
        if (belumSelesaiModalEl) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal && !belumSelesaiModalInstance) {
                try {
                    belumSelesaiModalInstance = new bootstrap.Modal(belumSelesaiModalEl);
                } catch (e) {
                    console.error("Gagal init belumSelesaiModal", e)
                }
            }
            if (belumSelesaiModalInstance) {
                belumSelesaiModalEl.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    currentComplaintIdForModal = (button && button.matches(
                            '[data-bs-toggle="modal"]')) ? button.getAttribute('data-id-complaint') :
                        null;

                    const refTicketIdWarningEl = belumSelesaiModalEl.querySelector(
                        '#refTicketIdWarning');
                    if (refTicketIdWarningEl) refTicketIdWarningEl.textContent =
                        currentComplaintIdForModal || 'N/A';
                    const refTicketIdTextEl = belumSelesaiModalEl.querySelector('#refTicketIdText');
                    if (refTicketIdTextEl) refTicketIdTextEl.textContent = currentComplaintIdForModal ||
                        'N/A';
                    const btnBuat = belumSelesaiModalEl.querySelector('#btnBuatTiketBaruDariModal');
                    if (btnBuat) {
                        btnBuat.disabled = false;
                        btnBuat.innerHTML = "Buat Tiket Baru";
                    }
                });

                const btnBuatTiketBaru = belumSelesaiModalEl.querySelector('#btnBuatTiketBaruDariModal');
                if (btnBuatTiketBaru) {
                    const newLink = btnBuatTiketBaru.cloneNode(true);
                    btnBuatTiketBaru.parentNode.replaceChild(newLink, btnBuatTiketBaru);
                    newLink.addEventListener('click', async function(e) {
                        e.preventDefault();
                        if (!currentComplaintIdForModal) {
                            alert("ID Tiket tidak valid.");
                            return;
                        }

                        const tanggapanResult = await tanggapiTiket(currentComplaintIdForModal,
                            'belum_selesai', this);
                        if (tanggapanResult && tanggapanResult.success) {
                            alert(tanggapanResult.message);
                            if (belumSelesaiModalInstance) belumSelesaiModalInstance.hide();
                            window.location.href =
                                `{{ route('ticketing.buat-laporan') }}?ref=${currentComplaintIdForModal}`;
                        }
                    });
                }
            }
        }
    });
</script>
