<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let countdownInterval = null;

    async function cariTiket() {
        const searchInput = document.getElementById('inputTiket').value.trim();
        const hasilArea = document.getElementById('hasilArea');
        const spinnerButton = document.querySelector(
            'button.btn-simpan'); // Pastikan selector ini tepat, atau gunakan ID jika tombol lacak punya ID

        if (searchInput === '') {
            hasilArea.innerHTML = `
                <div class="no-data">
                    <i class="bi bi-file-earmark-text"></i>
                    <div class="text-bold mt-2">Input kosong</div>
                    <div>Masukkan no tiket/no telepon/nama/no medrec untuk melihat status laporan Anda</div>
                </div>`;
            return;
        }

        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }

        hasilArea.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Mencari tiket...</p>
            </div>`;
        spinnerButton.disabled = true;
        spinnerButton.innerHTML =
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Melacak...`;

        try {
            const response = await fetch('{{ route('ticketing.lacak.search') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    searchInput: searchInput
                })
            });

            const result = await response.json();
            spinnerButton.disabled = false;
            spinnerButton.innerHTML = `<i class="bi bi-search"></i> Lacak`;

            if (result.success) {
                const tiket = result.tiket;
                const riwayatHtml = result.riwayat_penanganan.map(item => `
                    <div class="timeline-item">
                        <div class="fw-bold">${item.aktor} <span class="text-muted small fw-normal">${item.tanggal_aksi}</span></div>
                        <div class="timeline-title">${item.judul_aksi}</div>
                        <div>${item.deskripsi_aksi || ''}</div>
                    </div>
                `).join('');

                let detailTambahanHtml = '';
                // BAGIAN INI MENAMPILKAN BLOK KONFIRMASI SESUAI GAMBAR
                if (tiket.is_menunggu_konfirmasi) {
                    detailTambahanHtml = `
                        <div class="alert alert-warning mt-4 mb-4" id="konfirmasiArea-${tiket.id_complaint}">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock me-2 fs-4"></i>
                                <strong class="me-auto">Waktu Konfirmasi Tersisa</strong>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-primary" id="progressBar-${tiket.id_complaint}" role="progressbar" style="width: ${tiket.persen_waktu_konfirmasi || '100%'};" aria-valuenow="${(tiket.persen_waktu_konfirmasi || '100').replace('%','')}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p>Anda memiliki waktu <strong id="sisaWaktu-${tiket.id_complaint}">${tiket.waktu_konfirmasi_tersisa || 'Memuat...'}</strong> untuk mengkonfirmasi penyelesaian tiket ini. Jika tidak ada konfirmasi dalam 1x24 jam dari waktu penyelesaian internal, tiket akan otomatis ditutup.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-success btn-md" onclick="tanggapiTiket('${tiket.id_complaint}', 'selesai')">
                                    <i class="bi bi-check-circle pe-1"></i> Masalah Terselesaikan
                                </button>
                                <button class="btn btn-danger btn-md" onclick="tanggapiTiket('${tiket.id_complaint}', 'belum_selesai')">
                                    <i class="bi bi-x-circle pe-1"></i> Masalah Belum Terselesaikan
                                </button>
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

                let statusBadgeClass = 'bg-secondary';
                if (tiket.status === 'On Progress' || tiket.status === 'Dalam Proses') statusBadgeClass =
                    'bg-primary';
                else if (tiket.status === 'Menunggu Konfirmasi Pelapor') statusBadgeClass =
                    'bg-warning text-dark'; // Untuk badge status
                else if (tiket.status === 'Close' || tiket.status === 'Selesai') statusBadgeClass = 'bg-success';
                else if (tiket.status === 'Open' || tiket.status === 'Baru') statusBadgeClass =
                    'btn-simpan text-white'; // atau 'bg-info' atau kelas lain untuk status Open/Baru

                hasilArea.innerHTML = `
                    <div class="container border rounded p-3 p-md-4 mt-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong class="fs-5 text-break">Tiket: ${tiket.id_complaint}</strong>
                            <span class="status-badge ${statusBadgeClass} text-nowrap">${tiket.status}</span>
                        </div>
                        ${detailTambahanHtml} <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="info-label">Tanggal Dibuat:</div>
                                <div class="fw-bold">${tiket.tanggal_dibuat}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Tanggal Diperbarui:</div>
                                <div class="fw-bold">${tiket.tanggal_diperbarui}</div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3">
                            <div class="info-label">Ditangani Oleh:</div>
                            <div class="fw-bold">${tiket.ditangani_oleh}</div>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3">
                            <div class="info-label">Deskripsi Status Terkini:</div>
                            <div>${tiket.deskripsi_status_terkini}</div>
                        </div>
                        ${result.riwayat_penanganan && result.riwayat_penanganan.length > 0 ? `
                        <hr class="my-3">
                        <h5 class="fw-bold">Riwayat Penanganan</h5>
                        <p class="text-muted mb-2">Perkembangan penanganan tiket Anda</p>
                        <div class="timeline">${riwayatHtml}</div>
                        ` : `
                        <hr class="my-3">
                        <h5 class="fw-bold">Riwayat Penanganan</h5>
                        <p class="text-muted mb-2">Perkembangan penanganan tiket Anda</p>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="fw-bold">Pelapor <span class="text-muted small fw-normal">${tiket.tanggal_complaint_timelineFormat}</span></div>
                                <div class="timeline-title">Tiket Dibuat</div>
                                <div>Tiket ${tiket.id_complaint} telah dibuat.</div>
                            </div>
                        </div>
                        `}
                    </div>`;
                initializeRatingButtons(tiket.id_complaint); // Panggil setelah HTML dirender
            } else {
                hasilArea.innerHTML = `
                    <div class="no-data">
                        <i class="bi bi-emoji-frown"></i>
                        <div class="text-bold mt-2">Tidak Ditemukan</div>
                        <div>${result.message || 'Pastikan input yang Anda masukkan benar.'}</div>
                    </div>`;
            }
        } catch (error) {
            console.error('Error saat mencari tiket:', error);
            spinnerButton.disabled = false;
            spinnerButton.innerHTML = `<i class="bi bi-search"></i> Lacak`;
            hasilArea.innerHTML = `
                <div class="no-data">
                    <i class="bi bi-wifi-off"></i>
                    <div class="text-bold mt-2">Terjadi Kesalahan</div>
                    <div>Gagal terhubung ke server. Silakan coba lagi nanti.</div>
                </div>`;
        }
    }

    async function tanggapiTiket(idComplaint, jenisTanggapan) {
        const konfirmasiArea = document.getElementById(`konfirmasiArea-${idComplaint}`);
        let originalButtonHtmlSelesai = '';
        let originalButtonHtmlBelumSelesai = '';

        if (konfirmasiArea) {
            const btnSelesai = konfirmasiArea.querySelector('.btn-success');
            const btnBelumSelesai = konfirmasiArea.querySelector('.btn-danger');
            if (btnSelesai) originalButtonHtmlSelesai = btnSelesai.innerHTML;
            if (btnBelumSelesai) originalButtonHtmlBelumSelesai = btnBelumSelesai.innerHTML;

            if (jenisTanggapan === 'selesai' && btnSelesai) {
                btnSelesai.disabled = true;
                btnSelesai.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
                if (btnBelumSelesai) btnBelumSelesai.disabled = true;
            } else if (jenisTanggapan === 'belum_selesai' && btnBelumSelesai) {
                btnBelumSelesai.disabled = true;
                btnBelumSelesai.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
                if (btnSelesai) btnSelesai.disabled = true;
            }
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
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                document.getElementById('inputTiket').value = idComplaint; // Isi input dengan ID tiket
                cariTiket(); // Muat ulang detail tiket

                if (jenisTanggapan === 'selesai') {
                    var myModal = new bootstrap.Modal(document.getElementById(
                        'feedbackModal')); // Pastikan ID modal ini ada di HTML Anda
                    myModal.show();
                    document.getElementById('feedbackModal').dataset.idComplaint = idComplaint;
                } else if (jenisTanggapan === 'belum_selesai') {
                    var myModal = new bootstrap.Modal(document.getElementById(
                        'belumSelesaiModal')); // Pastikan ID modal ini ada di HTML Anda
                    const linkBuatTiketBaru = document.querySelector('#belumSelesaiModal .btn-simpan');
                    if (linkBuatTiketBaru) {
                        linkBuatTiketBaru.href = `{{ url('/ticketing/buat-laporan?ref=') }}${idComplaint}`;
                    }

                    const teksTiketTerkait = document.querySelector(
                        '#belumSelesaiModal .alert strong'); // Sesuaikan selector jika perlu
                    const spanTiketTerkait = document.querySelector(
                        '#belumSelesaiModal span strong'); // Sesuaikan selector jika perlu
                    if (teksTiketTerkait) teksTiketTerkait.textContent = idComplaint;
                    if (spanTiketTerkait) spanTiketTerkait.textContent = idComplaint;

                    myModal.show();
                }
            } else {
                alert(result.message || 'Gagal mengirim tanggapan.');
                if (konfirmasiArea) {
                    const btnSelesai = konfirmasiArea.querySelector('.btn-success');
                    const btnBelumSelesai = konfirmasiArea.querySelector('.btn-danger');
                    if (btnSelesai) {
                        btnSelesai.disabled = false;
                        btnSelesai.innerHTML = originalButtonHtmlSelesai;
                    }
                    if (btnBelumSelesai) {
                        btnBelumSelesai.disabled = false;
                        btnBelumSelesai.innerHTML = originalButtonHtmlBelumSelesai;
                    }
                }
            }
        } catch (error) {
            console.error('Error saat menanggapi tiket:', error);
            alert('Terjadi kesalahan koneksi saat mengirim tanggapan.');
            if (konfirmasiArea) {
                const btnSelesai = konfirmasiArea.querySelector('.btn-success');
                const btnBelumSelesai = konfirmasiArea.querySelector('.btn-danger');
                if (btnSelesai) {
                    btnSelesai.disabled = false;
                    btnSelesai.innerHTML = originalButtonHtmlSelesai;
                }
                if (btnBelumSelesai) {
                    btnBelumSelesai.disabled = false;
                    btnBelumSelesai.innerHTML = originalButtonHtmlBelumSelesai;
                }
            }
        }
    }

    function startCountdown(elementId, progressBarId, tglSelesaiInternalISO, konfirmasiAreaId) {
        const countDownElement = document.getElementById(elementId);
        const progressBarElement = document.getElementById(progressBarId);
        const konfirmasiAreaElement = document.getElementById(konfirmasiAreaId);

        if (!countDownElement || !progressBarElement || !tglSelesaiInternalISO || !konfirmasiAreaElement) {
            console.warn("Elemen countdown atau tanggal tidak ditemukan:", elementId, progressBarId,
                tglSelesaiInternalISO, konfirmasiAreaId);
            if (countDownElement) countDownElement.innerHTML = "N/A";
            return;
        }

        const tglSelesai = new Date(tglSelesaiInternalISO);
        const batasWaktuKonfirmasi = new Date(tglSelesai.getTime() + 24 * 60 * 60 *
            1000); // 24 jam dari tglSelesaiInternal

        if (countdownInterval) clearInterval(countdownInterval);

        countdownInterval = setInterval(() => {
            const sekarang = new Date();
            const sisaMs = batasWaktuKonfirmasi - sekarang;

            if (sisaMs < 0) {
                clearInterval(countdownInterval);
                countDownElement.innerHTML = "Waktu habis";
                progressBarElement.style.width = '0%';
                progressBarElement.classList.remove('bg-primary', 'bg-warning');
                progressBarElement.classList.add('bg-danger');

                const buttons = konfirmasiAreaElement.querySelectorAll('button');
                buttons.forEach(btn => btn.disabled = true);
                const pSisaWaktu = konfirmasiAreaElement.querySelector('p');
                if (pSisaWaktu) {
                    const existingStrong = pSisaWaktu.querySelector('strong');
                    if (existingStrong) existingStrong.textContent = "Waktu habis";
                    // Tambahkan pesan bahwa tiket akan ditutup otomatis
                    if (!pSisaWaktu.innerHTML.includes("otomatis ditutup oleh sistem")) {
                        // Periksa apakah pesan otomatis ditutup dari BE sudah ada atau belum
                        const isClosedBySystemMessageExists = Array.from(pSisaWaktu.childNodes).some(node =>
                            node.nodeType === Node.TEXT_NODE && node.textContent.includes(
                                "Tiket telah ditutup otomatis"));
                        if (!isClosedBySystemMessageExists && !pSisaWaktu.innerHTML.includes(
                                "<em>Tiket akan segera ditutup otomatis oleh sistem.</em>")) {
                            pSisaWaktu.innerHTML +=
                                "<br><em>Tiket akan segera ditutup otomatis oleh sistem.</em>";
                        }
                    }
                }
                // Di sini, idealnya ada pemanggilan ke backend untuk benar-benar menutup tiket.
                // Untuk saat ini, hanya UI yang diupdate.
                return;
            }

            const jam = Math.floor(sisaMs / (1000 * 60 * 60));
            const menit = Math.floor((sisaMs % (1000 * 60 * 60)) / (1000 * 60));
            const detik = Math.floor((sisaMs % (1000 * 60)) / 1000);
            countDownElement.innerHTML =
                `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;

            const totalDurasiMs = 24 * 60 * 60 * 1000; // Total durasi konfirmasi dalam milidetik
            const persenSisa = (sisaMs / totalDurasiMs) * 100;
            progressBarElement.style.width = Math.min(Math.max(persenSisa, 0), 100) +
                '%'; // Clamp antara 0-100%

            if (persenSisa < 20) { // Kurang dari 20% sisa waktu
                progressBarElement.classList.remove('bg-primary', 'bg-warning');
                progressBarElement.classList.add('bg-danger');
            } else if (persenSisa < 50) { // Kurang dari 50% sisa waktu
                progressBarElement.classList.remove('bg-primary', 'bg-danger');
                progressBarElement.classList.add('bg-warning');
            } else {
                progressBarElement.classList.remove('bg-warning', 'bg-danger');
                progressBarElement.classList.add('bg-primary');
            }

        }, 1000);
    }

    function initializeRatingButtons(idComplaint) {
        const feedbackModalElement = document.getElementById('feedbackModal');
        if (!feedbackModalElement) return;

        // Set dataset idComplaint pada modal setiap kali diinisialisasi
        feedbackModalElement.dataset.idComplaint = idComplaint;

        const ratingContainer = feedbackModalElement.querySelector(
            '#ratingContainer'); // Pastikan ID ini ada di modal HTML
        const kirimFeedbackButton = feedbackModalElement.querySelector('.btn-simpan'); // Tombol kirim di modal feedback

        if (ratingContainer) {
            const ratingButtons = ratingContainer.querySelectorAll(
                '.rating-btn'); // Pastikan class ini ada pada tombol rating

            // Hapus event listener lama dan tambahkan yang baru untuk rating buttons
            ratingButtons.forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn); // Ganti node lama dengan clone untuk hapus listener

                newBtn.addEventListener('click', function() {
                    const currentRating = parseInt(this.textContent);
                    // Reset semua tombol rating
                    ratingButtons.forEach(innerBtnOriginal => { // Gunakan original list untuk query
                        const iBtn = feedbackModalElement.querySelector(
                            `#ratingContainer .rating-btn:nth-child(${parseInt(innerBtnOriginal.textContent)})`
                        );
                        if (iBtn) {
                            iBtn.classList.remove('btn-primary', 'text-white');
                            iBtn.classList.add('btn-outline-secondary');
                        }
                    });
                    // Set tombol rating yang dipilih dan sebelumnya
                    for (let i = 1; i <= currentRating; i++) {
                        const rBtn = feedbackModalElement.querySelector(
                            `#ratingContainer .rating-btn:nth-child(${i})`);
                        if (rBtn) {
                            rBtn.classList.add('btn-primary', 'text-white');
                            rBtn.classList.remove('btn-outline-secondary');
                        }
                    }
                    if (kirimFeedbackButton) kirimFeedbackButton.dataset.rating = currentRating;
                });
            });
        }

        if (kirimFeedbackButton) {
            // Hapus event listener lama dan tambahkan yang baru untuk tombol kirim
            const newKirimBtn = kirimFeedbackButton.cloneNode(true);
            kirimFeedbackButton.parentNode.replaceChild(newKirimBtn, kirimFeedbackButton);

            newKirimBtn.onclick = function() { // Gunakan onclick atau addEventListener
                const currentIdComplaintFromModal = feedbackModalElement.dataset
                    .idComplaint; // Ambil dari dataset modal
                const ratingToSend = parseInt(this.dataset.rating) || 0; // Ambil dari dataset tombol kirim
                const feedbackText = feedbackModalElement.querySelector('#feedbackText')
                    .value; // Pastikan ID ini ada
                kirimFeedback(currentIdComplaintFromModal, ratingToSend, feedbackText);
            };
        }
    }

    async function kirimFeedback(idComplaint, rating, feedbackText) {
        const kirimButton = document.querySelector('#feedbackModal .btn-simpan');
        let originalButtonHtml = '';
        if (kirimButton) {
            originalButtonHtml = kirimButton.innerHTML;
            kirimButton.disabled = true;
            kirimButton.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...`;
        }

        try {
            const response = await fetch('{{ route('ticketing.simpan-feedback') }}', { // Pastikan route ini ada
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_complaint: idComplaint,
                    rating: rating,
                    feedback: feedbackText
                })
            });
            const result = await response.json();

            if (kirimButton) {
                kirimButton.disabled = false;
                kirimButton.innerHTML = originalButtonHtml;
            }

            if (result.success) {
                alert('Feedback berhasil dikirim!');
                var feedbackModalEl = document.getElementById('feedbackModal');
                if (feedbackModalEl) {
                    var feedbackModalInstance = bootstrap.Modal.getInstance(feedbackModalEl);
                    if (feedbackModalInstance) feedbackModalInstance.hide();
                }
                // Reset form feedback setelah berhasil
                const feedbackTextArea = document.getElementById('feedbackText');
                if (feedbackTextArea) feedbackTextArea.value = '';
                const ratingContainer = document.getElementById('ratingContainer');
                if (ratingContainer) {
                    ratingContainer.querySelectorAll('.rating-btn').forEach(btn => {
                        btn.classList.remove('btn-primary', 'text-white');
                        btn.classList.add('btn-outline-secondary');
                    });
                }
                if (kirimButton) kirimButton.dataset.rating = 0; // Reset rating di tombol

            } else {
                let errorMessage = result.message || 'Gagal mengirim feedback.';
                if (result.errors) {
                    for (const key in result.errors) {
                        errorMessage += `\n- ${result.errors[key].join(', ')}`;
                    }
                }
                alert(errorMessage);
            }
        } catch (error) {
            if (kirimButton) {
                kirimButton.disabled = false;
                kirimButton.innerHTML = originalButtonHtml;
            }
            console.error('Error mengirim feedback:', error);
            alert('Terjadi kesalahan koneksi saat mengirim feedback.');
        }
    }

    // Event listener untuk tombol Enter pada input pencarian
    document.getElementById('inputTiket').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Mencegah submit form jika input ada di dalam form
            cariTiket();
        }
    });

    // Event listener untuk modal feedback saat ditutup (hidden)
    const feedbackModalEl = document.getElementById('feedbackModal');
    if (feedbackModalEl) {
        feedbackModalEl.addEventListener('hidden.bs.modal', function() {
            // Reset form di dalam modal feedback
            const feedbackTextArea = this.querySelector('#feedbackText'); // 'this' merujuk ke modal
            if (feedbackTextArea) feedbackTextArea.value = '';

            const ratingContainer = this.querySelector('#ratingContainer');
            if (ratingContainer) {
                ratingContainer.querySelectorAll('.rating-btn').forEach(btn => {
                    btn.classList.remove('btn-primary', 'text-white');
                    btn.classList.add('btn-outline-secondary');
                });
            }
            const kirimFeedbackButton = this.querySelector('.btn-simpan');
            if (kirimFeedbackButton) kirimFeedbackButton.dataset.rating = 0; // Reset rating
        });
    }
</script>
