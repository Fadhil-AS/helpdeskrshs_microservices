{{-- footerLacakTicketing.blade.php --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>

<script>
    let countdownInterval = null;

    async function cariTiket() {
        const searchInput = document.getElementById('inputTiket');
        const hasilArea = document.getElementById('hasilArea');
        const spinnerButton = document.querySelector('.lacak-container button.btn-simpan[onclick="cariTiket()"]');

        if (!searchInput || !hasilArea) {
            console.error("Elemen #inputTiket atau #hasilArea tidak ditemukan.");
            if (hasilArea) hasilArea.innerHTML =
                `<div class="no-data text-danger">Error: Elemen penting halaman tidak ditemukan.</div>`;
            return;
        }
        const searchInputValue = searchInput.value.trim();

        if (searchInputValue === '') {
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
                    /* Abaikan jika error parsing JSON dari respons error */
                }
                console.error('Error HTTP dari searchTicket:', errorText);
                hasilArea.innerHTML =
                    `<div class="no-data"><i class="bi bi-wifi-off"></i><div class="text-bold mt-2">Gagal Memuat Data</div><div>${errorText}. Silakan coba lagi nanti.</div></div>`;
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
                    `<div class="no-data"><i class="bi bi-exclamation-triangle-fill"></i><div class="text-bold mt-2">Format Respons Salah</div><div>Server memberikan respons yang tidak terduga. Periksa console untuk detail.</div></div>`;
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

                // 1. Buat entri timeline awal "Tiket Dibuat"
                const initialTimelineEntryHtml = `
                    <div class="timeline-item">
                        <div class="fw-bold">Pelapor <span class="text-muted small fw-normal">${tiket.tanggal_complaint_timelineFormat || 'N/A'}</span></div>
                        <div class="timeline-title">Tiket Dibuat</div>
                        <div>Tiket ${tiket.id_complaint || 'N/A'} telah dibuat.</div>
                    </div>
                `;

                // 2. Buat HTML untuk riwayat penanganan tambahan dari server (jika ada)
                const additionalRiwayatHtml = (result.riwayat_penanganan && Array.isArray(result
                        .riwayat_penanganan) && result.riwayat_penanganan.length > 0) ?
                    result.riwayat_penanganan.map(item => `
                        <div class="timeline-item">
                            <div class="fw-bold">${item.aktor || 'N/A'} <span class="text-muted small fw-normal">${item.tanggal_aksi || 'N/A'}</span></div>
                            <div class="timeline-title">${item.judul_aksi || 'N/A'}</div>
                            <div>${item.deskripsi_aksi || ''}</div>
                        </div>
                    `).join('') :
                    '';

                // 3. Gabungkan entri awal dengan riwayat tambahan
                const fullRiwayatHtml = initialTimelineEntryHtml + additionalRiwayatHtml;

                // 4. Siapkan HTML untuk seluruh bagian timeline
                const timelineSectionHtml = `
                    <hr class="my-3">
                    <h5 class="fw-bold">Riwayat Penanganan</h5>
                    <p class="text-muted mb-2">Perkembangan penanganan tiket Anda</p>
                    <div class="timeline">${fullRiwayatHtml}</div>
                `;


                let detailTambahanHtml = '';
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
                            <p>Anda memiliki waktu <strong id="sisaWaktu-${tiket.id_complaint}">${tiket.waktu_konfirmasi_tersisa || 'Memuat...'}</strong> untuk mengkonfirmasi penyelesaian tiket ini. Jika tidak ada konfirmasi, tiket akan otomatis ditutup.</p>
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
                else if (tiket.status === 'Menunggu Konfirmasi Pelapor' || tiket.status === 'Menunggu Konfirmasi')
                    statusBadgeClass = 'bg-warning text-dark'; // Ditambahkan 'Menunggu Konfirmasi'
                else if (tiket.status === 'Close' || tiket.status === 'Selesai') statusBadgeClass = 'bg-success';
                else if (tiket.status === 'Open' || tiket.status === 'Baru' || tiket.status === 'Banding')
                    statusBadgeClass = 'btn-simpan text-white'; // Ditambahkan 'Banding'


                hasilArea.innerHTML = `
                    <div class="container border rounded p-3 p-md-4 mt-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong class="fs-5 text-break">Tiket: ${tiket.id_complaint}</strong>
                            <span class="status-badge ${statusBadgeClass} text-nowrap">${tiket.status}</span>
                        </div>
                        ${detailTambahanHtml}
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="info-label">Tanggal Dibuat:</div>
                                <div class="fw-bold">${tiket.tanggal_dibuat || 'N/A'}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Tanggal Diperbarui:</div>
                                <div class="fw-bold">${tiket.tanggal_diperbarui || 'N/A'}</div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3">
                            <div class="info-label">Ditangani Oleh:</div>
                            <div class="fw-bold">${tiket.ditangani_oleh || 'N/A'}</div>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3">
                            <div class="info-label">Deskripsi Status Terkini:</div>
                            <div>${tiket.deskripsi_status_terkini || 'N/A'}</div>
                        </div>
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

    async function tanggapiTiket(idComplaint, jenisTanggapan) {
        const konfirmasiArea = document.getElementById(`konfirmasiArea-${idComplaint}`);
        let originalButtonHtmlSelesai = '';
        let originalButtonHtmlBelumSelesai = '';
        let btnSelesai = konfirmasiArea ? konfirmasiArea.querySelector('.btn-success') : null;
        let btnBelumSelesai = konfirmasiArea ? konfirmasiArea.querySelector('.btn-danger') : null;

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

        const reEnableButtons = () => {
            if (btnSelesai) {
                btnSelesai.disabled = false;
                btnSelesai.innerHTML = originalButtonHtmlSelesai;
            }
            if (btnBelumSelesai) {
                btnBelumSelesai.disabled = false;
                btnBelumSelesai.innerHTML = originalButtonHtmlBelumSelesai;
            }
        };

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

            if (!response.ok) {
                let errorText = `Error HTTP: ${response.status} ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorText = errorData.message || errorText;
                } catch (e) {
                    /* ignore */
                }
                console.error('Error HTTP dari tanggapiTiket:', errorText);
                alert(`Gagal mengirim tanggapan: ${errorText}`);
                reEnableButtons();
                return;
            }

            let result;
            try {
                result = await response.json();
            } catch (e) {
                const responseText = await response.text();
                console.error('Gagal parse JSON dari tanggapiTiket:', e, "\nResponse Text:", responseText);
                alert('Server memberikan respons yang tidak terduga. Periksa console.');
                reEnableButtons();
                return;
            }

            if (result.success) {
                alert(result.message);
                const inputTiketEl = document.getElementById('inputTiket');

                if (jenisTanggapan === 'belum_selesai') {
                    // PERUBAHAN: Redirect langsung ke form pengaduan
                    window.location.href = `{{ route('ticketing.buat-laporan') }}?ref=${idComplaint}`;
                } else if (jenisTanggapan === 'selesai') {
                    if (inputTiketEl) inputTiketEl.value = idComplaint;
                    cariTiket();
                }
            } else {
                alert(result.message || 'Gagal mengirim tanggapan dari server.');
                reEnableButtons();
            }
        } catch (error) {
            console.error('Error saat menanggapi tiket (catch utama):', error);
            alert('Terjadi kesalahan teknis saat memproses tanggapan Anda. Periksa console.');
            reEnableButtons();
        }
    }

    function startCountdown(elementId, progressBarId, tglSelesaiInternalISO, konfirmasiAreaId) {
        const countDownElement = document.getElementById(elementId);
        const progressBarElement = document.getElementById(progressBarId);
        const konfirmasiAreaElement = document.getElementById(konfirmasiAreaId);

        if (!countDownElement || !progressBarElement || !tglSelesaiInternalISO || !konfirmasiAreaElement) {
            console.warn("Elemen countdown tidak lengkap atau tanggal tidak valid:", {
                elementId,
                progressBarId,
                tglSelesaiInternalISO,
                konfirmasiAreaId
            });
            if (countDownElement) countDownElement.innerHTML = "N/A";
            return;
        }
        const tglSelesai = new Date(tglSelesaiInternalISO);
        if (isNaN(tglSelesai.getTime())) {
            console.error("Format tglSelesaiInternalISO tidak valid:", tglSelesaiInternalISO);
            if (countDownElement) countDownElement.innerHTML = "Err:Tgl";
            return;
        }
        const batasWaktuKonfirmasi = new Date(tglSelesai.getTime() + 24 * 60 * 60 * 1000);
        if (countdownInterval) clearInterval(countdownInterval);

        countdownInterval = setInterval(() => {
            const sekarang = new Date();
            const sisaMs = batasWaktuKonfirmasi - sekarang;
            if (sisaMs < 0) {
                clearInterval(countdownInterval);
                if (countDownElement) countDownElement.innerHTML = "Waktu habis";
                if (progressBarElement) {
                    progressBarElement.style.width = '0%';
                    progressBarElement.classList.remove('bg-primary', 'bg-warning');
                    progressBarElement.classList.add('bg-danger');
                }
                if (konfirmasiAreaElement) {
                    const buttons = konfirmasiAreaElement.querySelectorAll('button');
                    buttons.forEach(btn => btn.disabled = true);
                    const pSisaWaktuStrong = konfirmasiAreaElement.querySelector('p strong');
                    if (pSisaWaktuStrong) pSisaWaktuStrong.textContent = "Waktu habis";

                    const pElementForMessage = konfirmasiAreaElement.querySelector('p');
                    if (pElementForMessage && !pElementForMessage.innerHTML.includes(
                            "Tiket akan segera ditutup otomatis oleh sistem.")) {
                        pElementForMessage.innerHTML +=
                            "<br><em>Tiket akan segera ditutup otomatis oleh sistem.</em>";
                    }
                }
                return;
            }
            const jam = Math.floor(sisaMs / (1000 * 60 * 60));
            const menit = Math.floor((sisaMs % (1000 * 60 * 60)) / (1000 * 60));
            const detik = Math.floor((sisaMs % (1000 * 60)) / 1000);
            if (countDownElement) countDownElement.innerHTML =
                `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;

            const totalDurasiMs = 24 * 60 * 60 * 1000;
            const persenSisa = (sisaMs / totalDurasiMs) * 100;
            if (progressBarElement) {
                progressBarElement.style.width = Math.min(Math.max(persenSisa, 0), 100) + '%';
                progressBarElement.classList.remove('bg-primary', 'bg-warning', 'bg-danger');
                if (persenSisa < 20) {
                    progressBarElement.classList.add('bg-danger');
                } else if (persenSisa < 50) {
                    progressBarElement.classList.add('bg-warning');
                } else {
                    progressBarElement.classList.add('bg-primary');
                }
            }
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputTiketEl = document.getElementById('inputTiket');
        if (inputTiketEl) {
            inputTiketEl.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    cariTiket();
                }
            });
        }
    });
</script>
