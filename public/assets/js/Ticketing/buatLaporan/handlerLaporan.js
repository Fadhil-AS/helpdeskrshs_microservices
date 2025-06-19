document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPengaduan');
    const submitButton = form.querySelector('button[type="submit"]');
    const formMessageDiv = document.getElementById('formMessage');
    const klasifikasiSelect = document.getElementById('ID_KLASIFIKASI');

    const buktiPendukungFileInput = document.getElementById('buktiPendukungFile');
    const buktiPendukungDropAreaLabel = document.getElementById('buktiPendukungDropZone');
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
        if (!allowedTypes.includes(file.type)) return { valid: false, message: `Tipe file tidak diizinkan: ${file.name}.` };
        if (file.size > maxSize) return { valid: false, message: `Ukuran file ${file.name} terlalu besar (Maks. 5MB).` };
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
                let preview = file.type.startsWith('image/')
                    ? `<img src="${URL.createObjectURL(file)}" alt="${file.name}" />`
                    : `<div class="file-icon d-flex justify-content-center align-items-center h-100"><i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i></div>`;

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
        const selectedOptionText = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim();
        const wrapperNama = document.getElementById('wrapper_nama');
        const wrapperNoTlpn = document.getElementById('wrapper_no_tlpn');
        const wrapperNoMedrec = document.getElementById('wrapper_no_medrec');
        const inputNama = document.querySelector('[name="NAME"]');
        const inputNoTlpn = document.querySelector('[name="NO_TLPN"]');
        const fileLabel = document.getElementById('buktiPendukungLabel');
        const labelNama = wrapperNama.querySelector('label');
        const labelTelepon = wrapperNoTlpn.querySelector('label');

        wrapperNama.style.display = 'block';
        wrapperNoTlpn.style.display = 'block';
        wrapperNoMedrec.style.display = 'block';
        inputNama.required = true;
        inputNoTlpn.required = true;
        labelNama.innerHTML = 'Nama Lengkap';
        labelTelepon.innerHTML = 'Nomor Telepon';
        fileLabel.innerHTML = 'Bukti Pendukung (Opsional)';

        if (selectedOptionText === 'Sponsorship') {
            inputNama.required = false;
            inputNoTlpn.required = false;
            labelNama.innerHTML = 'Nama Lengkap';
            labelTelepon.innerHTML = 'Nomor Telepon';
            fileLabel.innerHTML = 'Surat Undangan (Wajib)';
            wrapperNoMedrec.style.display = 'none';
        } else if (selectedOptionText === 'Gratifikasi') {
            fileLabel.innerHTML = 'Bukti Pendukung (Wajib)';
            wrapperNama.style.display = 'none';
            wrapperNoTlpn.style.display = 'none';
            wrapperNoMedrec.style.display = 'none';
            inputNama.required = false;
            inputNoTlpn.required = false;
        }
    }

    klasifikasiSelect.addEventListener('change', handleKlasifikasiChange);

    renderFileUI();
    buktiPendukungFileInput.addEventListener('change', (e) => processFiles(e.target.files));
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        buktiPendukungDropAreaLabel.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
    });
    buktiPendukungDropAreaLabel.addEventListener('drop', e => processFiles(e.dataTransfer.files));

    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        formMessageDiv.innerHTML = '';

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            form.querySelector(':invalid:not(fieldset)')?.focus();
            return;
        }

        const selectedKlasifikasi = klasifikasiSelect.options[klasifikasiSelect.selectedIndex].text.trim();
        if ((selectedKlasifikasi === 'Gratifikasi' || selectedKlasifikasi === 'Sponsorship') && validBuktiPendukungFiles.length === 0) {
            formMessageDiv.innerHTML = `<div class="alert alert-danger">Untuk klasifikasi '${selectedKlasifikasi}', bukti pendukung wajib diunggah.</div>`;
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';

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
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
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

        if (uploadError) {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Kirim Laporan';
            return;
        }

        const finalFormData = new FormData(form);
        finalFormData.append('upload_id', uploadId);
        tempPaths.forEach(path => finalFormData.append('uploaded_files[]', path));
        finalFormData.delete('bukti_pendukung[]');

        try {
            const finalResponse = await fetch(form.action, {
                method: 'POST',
                body: finalFormData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const finalResult = await finalResponse.json();

            if (!finalResult.success) {
                let errorHtml = finalResult.message || 'Terjadi kesalahan.';
                if(finalResult.errors) {
                    errorHtml += '<ul>' + Object.values(finalResult.errors).map(e => `<li>${e[0]}</li>`).join('') + '</ul>';
                }
                throw new Error(errorHtml);
            }

            formMessageDiv.innerHTML = `<div class="alert alert-success">${finalResult.message}</div>`;
            form.reset();
            validBuktiPendukungFiles.length = 0;
            renderFileUI();
            handleKlasifikasiChange();
            setTimeout(() => window.location.href = redirectUrl, 3000);

        } catch (error) {
            formMessageDiv.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
        } finally {
             submitButton.disabled = false;
             submitButton.innerHTML = 'Kirim Laporan';
        }
    });

    handleKlasifikasiChange();
});
