document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPengaduan');
    const submitButton = form.querySelector('button[type="submit"]');
    const formMessageDiv = document.getElementById('formMessage');
    const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');
    const jenisPelaporSelect = document.getElementById('jenisPelapor');

    const buktiPendukungFileInput = document.getElementById('buktiPendukungFile');
    const buktiPendukungDropAreaLabel = document.getElementById('buktiPendukungDropZone');
    if (!klasifikasiSelect || !jenisPelaporSelect || !buktiPendukungDropAreaLabel) {
        console.error('Satu atau lebih elemen form penting tidak ditemukan. Pastikan ID_KLASIFIKASI, jenisPelapor, dan buktiPendukungDropZone ada di HTML.');
        return;
    }
    const uploadBoxContent = buktiPendukungDropAreaLabel.querySelector('.upload-box-content');
    const buktiErrorContainer = document.getElementById('buktiPendukungFileErrors');
    const originalUploadBoxHTML = `<div class="initial-prompt text-center"><i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem;"></i><p class="mt-2 mb-0 upload-box-text">Klik untuk upload <span class="fw-light">atau drag and drop</span></p><small class="text-muted upload-box-hint">Format: JPG, PNG, PDF (Maks. 5MB).</small></div>`;

    let validBuktiPendukungFiles = [];

    const uploadUrl = form.dataset.uploadUrl;
    const csrfToken = form.dataset.csrfToken;
    const redirectUrl = form.dataset.redirectUrl;

    function validateFile(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        const maxSize = 5 * 1024 * 1024;
        if (!allowedTypes.includes(file.type)) {
            return { valid: false, message: `Tipe file tidak diizinkan: ${file.name}.` };
        }
        if (file.size > maxSize) {
            return { valid: false, message: `Ukuran file ${file.name} terlalu besar (Maks. 5MB).` };
        }
        return { valid: true };
    }

    function renderFileUI() {
        uploadBoxContent.innerHTML = '';
        if (validBuktiPendukungFiles.length === 0) {
            uploadBoxContent.innerHTML = originalUploadBoxHTML;
        } else {
            const grid = document.createElement('div');
            grid.className = 'd-flex flex-wrap justify-content-start align-items-stretch gap-2';
            validBuktiPendukungFiles.forEach((file, index) => {
                const fileBox = document.createElement('div');
                fileBox.className = 'file-item-box';
                let preview = file.type.startsWith('image/') ?
                    `<img src="${URL.createObjectURL(file)}" alt="${file.name}" />` :
                    `<div class="file-icon d-flex justify-content-center align-items-center h-100"><i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i></div>`;

                fileBox.innerHTML = `
                    <div class="file-preview-section">${preview}</div>
                    <div class="file-details-section"><div class="file-name" title="${file.name}">${file.name}</div></div>
                    <div class="file-remove-section"><button type="button" class="btn btn-sm btn-danger btn-remove-file" data-index="${index}">&times;</button></div>`;
                grid.appendChild(fileBox);
            });
            uploadBoxContent.appendChild(grid);
        }
    }

    function processFiles(files) {
        buktiErrorContainer.innerHTML = '';
        buktiPendukungDropAreaLabel.classList.remove('is-invalid');
        Array.from(files).forEach(file => {
            const validation = validateFile(file);
            const isDuplicate = validBuktiPendukungFiles.some(f => f.name === file.name && f.size === file.size);
            if (validation.valid && !isDuplicate) {
                validBuktiPendukungFiles.push(file);
            } else if (!validation.valid) {
                buktiErrorContainer.innerHTML += `<p class="mb-1 text-danger">${validation.message}</p>`;
            }
        });
        renderFileUI();
        buktiPendukungFileInput.value = '';
    }

    uploadBoxContent.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-file')) {
            e.preventDefault();
            const indexToRemove = parseInt(e.target.closest('.btn-remove-file').dataset.index, 10);
            validBuktiPendukungFiles.splice(indexToRemove, 1);
            renderFileUI();
        }
    });

    function handleKlasifikasiChange() {
        const selectedOptionText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex]?.text.trim() || '';
        const wrapperNama = document.getElementById('wrapper_nama');
        const wrapperNoTlpn = document.getElementById('wrapper_no_tlpn');
        const wrapperNoMedrec = document.getElementById('wrapper_no_medrec');
        const inputNama = document.querySelector('[name="NAME"]');
        const inputNoTlpn = document.querySelector('[name="NO_TLPN"]');
        const inputNoMedrec = document.querySelector('[name="NO_MEDREC"]');
        const fileLabel = document.getElementById('buktiPendukungLabel');
        const labelNama = wrapperNama.querySelector('label');
        const labelTelepon = wrapperNoTlpn.querySelector('label');

        buktiPendukungFileInput.required = false;

        if (selectedOptionText === 'Gratifikasi') {
            wrapperNama.style.display = 'none';
            inputNama.required = false;
            inputNama.disabled = true;

            wrapperNoTlpn.style.display = 'none';
            inputNoTlpn.required = false;
            inputNoTlpn.disabled = true;

            wrapperNoMedrec.style.display = 'none';
            inputNoMedrec.disabled = true;

            fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';

        } else if (selectedOptionText === 'Sponsorship') {
            jenisPelaporSelect.value = 'Non-Pasien';

            wrapperNama.style.display = 'block';
            inputNama.required = false;
            inputNama.disabled = false;
            labelNama.innerHTML = 'Nama Lengkap (Opsional)';

            wrapperNoTlpn.style.display = 'block';
            inputNoTlpn.required = false;
            inputNoTlpn.disabled = false;
            labelTelepon.innerHTML = 'Nomor Telepon (Opsional)';

            wrapperNoMedrec.style.display = 'none';
            inputNoMedrec.disabled = true;

            fileLabel.innerHTML = 'Surat Undangan (Wajib)';

        } else {
            wrapperNama.style.display = 'block';
            inputNama.required = true;
            inputNama.disabled = false;
            labelNama.innerHTML = 'Nama Lengkap';

            wrapperNoTlpn.style.display = 'block';
            inputNoTlpn.required = true;
            inputNoTlpn.disabled = false;
            labelTelepon.innerHTML = 'Nomor Telepon';

            wrapperNoMedrec.style.display = 'block';
            inputNoMedrec.disabled = false;

            fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';
        }
    }


    function handleJenisPelaporChange() {
        const selectedPelapor = jenisPelaporSelect.value;
        const sponsorshipOption = Array.from(klasifikasiSelect.options).find(opt => opt.text.trim() === 'Sponsorship');

        if (sponsorshipOption) {
            sponsorshipOption.disabled = (selectedPelapor === 'Pasien');
            if (sponsorshipOption.disabled && klasifikasiSelect.value === sponsorshipOption.value) {
                klasifikasiSelect.value = '';
                handleKlasifikasiChange();
            }
        }
    }

    klasifikasiSelect.addEventListener('change', handleKlasifikasiChange);
    jenisPelaporSelect.addEventListener('change', handleJenisPelaporChange);

    buktiPendukungFileInput.addEventListener('change', (e) => processFiles(e.target.files));
    ['dragenter', 'dragover'].forEach(eventName => {
        buktiPendukungDropAreaLabel.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            buktiPendukungDropAreaLabel.classList.add('is-dragging-over');
        }, false);
    });

    ['dragleave'].forEach(eventName => {
        buktiPendukungDropAreaLabel.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            buktiPendukungDropAreaLabel.classList.remove('is-dragging-over');
        }, false);
    });
    buktiPendukungDropAreaLabel.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();

        buktiPendukungDropAreaLabel.classList.remove('is-dragging-over');

        processFiles(e.dataTransfer.files);
    }, false);


    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        handleKlasifikasiChange();
        console.log('PENANDA 1: Proses submit dimulai.');

        formMessageDiv.innerHTML = '';
        buktiErrorContainer.innerHTML = '';
        buktiPendukungDropAreaLabel.classList.remove('is-invalid');
        form.classList.remove('was-validated');

        let isFormValid = true;
        console.log('--- MEMERIKSA VALIDITAS SETIAP KOLOM ---');
        for (const element of form.elements) {
            if (element.name && !element.checkValidity()) {
                console.error(`KOLOM TIDAK VALID:`, {
                    nama_kolom: element.name,
                    nilai: element.value,
                    wajib_diisi: element.required,
                    non_aktif: element.disabled,
                    detail_validitas: element.validity
                });
                isFormValid = false;
            }
        }
        console.log('--- PEMERIKSAAN SELESAI ---');

        if (!isFormValid) {
            console.error('PROSES BERHENTI: Validasi gagal karena ada kolom yang tidak valid (lihat detail di atas).');
            form.classList.add('was-validated');
            return;
        }
        console.log('PENANDA 2: Validasi form dasar berhasil.');

        const selectedKlasifikasi = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim();
        if ((selectedKlasifikasi === 'Gratifikasi' || selectedKlasifikasi === 'Sponsorship') && validBuktiPendukungFiles.length === 0) {
            console.error('PROSES BERHENTI: Validasi file untuk klasifikasi wajib gagal.');
            let pesanError = 'Bukti pendukung wajib diunggah.';
            if (selectedKlasifikasi === 'Sponsorship') {
                pesanError = 'Surat undangan wajib diunggah.';
            }
            buktiErrorContainer.innerHTML = `<div class="text-danger mt-1">${pesanError}</div>`;
            buktiPendukungDropAreaLabel.classList.add('is-invalid');
            buktiPendukungDropAreaLabel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        console.log('PENANDA 3: Validasi file manual berhasil.');

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';
        console.log('PENANDA 4: Memulai proses upload file temporer...');

        const uploadId = Date.now().toString();
        const tempPaths = [];
        let uploadError = false;

        for (const file of validBuktiPendukungFiles) {
            const fileFormData = new FormData();
            fileFormData.append('file', file);
            fileFormData.append('upload_id', uploadId);
            try {
                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    body: fileFormData,
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (!result.success) throw new Error(result.message || 'Gagal unggah file.');
                tempPaths.push(result.path);
            } catch (error) {
                formMessageDiv.innerHTML = `<div class="alert alert-danger">Gagal mengunggah file: ${error.message}</div>`;
                uploadError = true;
                break;
            }
        }
        console.log('PENANDA 5: Proses upload file temporer selesai.');

        if (uploadError) {
            console.error('PROSES BERHENTI: Terjadi error saat upload file temporer.');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Kirim Laporan';
            return;
        }

        console.log('PENANDA 6: Membangun data akhir untuk dikirim ke server.');
        const finalFormData = new FormData();
        finalFormData.append('ID_KLASIFIKASI', klasifikasiSelect.value);
        finalFormData.append('jenis_pelapor', jenisPelaporSelect.value);
        finalFormData.append('ISI_COMPLAINT', form.querySelector('[name="ISI_COMPLAINT"]').value);
        finalFormData.append('NAME', form.querySelector('[name="NAME"]').value);
        finalFormData.append('NO_TLPN', form.querySelector('[name="NO_TLPN"]').value);
        finalFormData.append('NO_MEDREC', form.querySelector('[name="NO_MEDREC"]').value);

        const refInput = form.querySelector('[name="ID_COMPLAINT_REFERENSI"]');
        if (refInput) {
            finalFormData.append('ID_COMPLAINT_REFERENSI', refInput.value);
        }
        finalFormData.append('upload_id', uploadId);
        tempPaths.forEach(path => finalFormData.append('uploaded_files[]', path));

        try {
            console.log('PENANDA 7: Mengirim data akhir ke server...');
            const finalResponse = await fetch(form.action, {
                method: 'POST',
                body: finalFormData,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
            });

            console.log('PENANDA 8: Mendapat respons dari server.');
            const finalResult = await finalResponse.json();

            console.log('Isi Respons Aktual dari Server:', finalResult);
            if (!finalResult.success) {
                console.error('PROSES GAGAL: Server merespons dengan status gagal.', finalResult);
                let errorHtml = finalResult.message || 'Terjadi kesalahan.';
                if (finalResult.errors) {
                    errorHtml += '<ul>' + Object.values(finalResult.errors).map(e => `<li>${e[0]}</li>`).join('') + '</ul>';
                }
                throw new Error(errorHtml);
            }

            form.reset();
            validBuktiPendukungFiles.length = 0;
            renderFileUI();
            handleKlasifikasiChange();
            handleJenisPelaporChange();

            const successModalEl = document.getElementById('successModal');
            const ticketNumberEl = document.getElementById('modalTicketNumber');
            const okButton = document.getElementById('modalOkButton');

            if (successModalEl && ticketNumberEl && okButton) {
                formMessageDiv.innerHTML = '';

                if (finalResult.ticket_id) {
                    ticketNumberEl.innerHTML = `Nomor tiket anda: <br><strong class="fs-4">${finalResult.ticket_id}</strong>`;
                } else {
                    ticketNumberEl.textContent = finalResult.message || 'Laporan Anda telah berhasil dikirim.';
                }

                okButton.onclick = () => {
                    window.location.href = redirectUrl;
                };

                const successModal = new bootstrap.Modal(successModalEl);
                successModal.show();
            } else {
                console.error('Elemen modal tidak ditemukan!');
                formMessageDiv.innerHTML = `<div class="alert alert-success">${finalResult.message || 'Laporan berhasil dikirim!'}</div>`;
                setTimeout(() => window.location.href = redirectUrl, 3000);
            }

        } catch (error) {
            console.error('PROSES ERROR: Terjadi kesalahan di blok try-catch akhir.', error);
            formMessageDiv.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
            submitButton.disabled = false;
            submitButton.innerHTML = 'Kirim Laporan';
        }
    });

    renderFileUI();
    handleKlasifikasiChange();
    handleJenisPelaporChange();
});
