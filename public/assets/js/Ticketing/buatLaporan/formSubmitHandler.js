function setupFormSubmitHandler(
    formPengaduan,
    submitButton,
    formMessageDiv,
    getValidBuktiPendukungFiles,
    csrfToken,
    uploadFileRoute,
    storeLaporanRoute,
    resetUIAfterSuccess
) {
    if (formPengaduan && submitButton) {
        formPengaduan.addEventListener('submit', async function(event) {
            event.preventDefault();
            if (formMessageDiv) formMessageDiv.innerHTML = '';
            submitButton.disabled = true;
            submitButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim Laporan...';

            const allFilesToUpload = [];

            const currentValidBuktiFiles = getValidBuktiPendukungFiles(); // Ambil file yang sudah divalidasi oleh UI bukti pendukung
            currentValidBuktiFiles.forEach(file => {
                allFilesToUpload.push({ file: file, type: 'bukti_pendukung' });
            });

            const uploadId = Date.now().toString() + Math.random().toString(36).substr(2, 9);
            const uploadedFileTemporaryPaths = [];
            let uploadErrorOccurred = false;

            for (const fileData of allFilesToUpload) {
                const fileFormData = new FormData();
                fileFormData.append('file', fileData.file);
                fileFormData.append('upload_id', uploadId);
                fileFormData.append('_token', csrfToken);

                try {
                    submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah ${fileData.file.name}...`;
                    const response = await fetch(uploadFileRoute, {
                        method: 'POST',
                        body: fileFormData,
                        headers: {'Accept': 'application/json'}
                    });
                    const result = await response.json();
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Gagal mengunggah file: ' + fileData.file.name);
                    }
                    uploadedFileTemporaryPaths.push(result.path);
                } catch (error) {
                    if (formMessageDiv) formMessageDiv.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
                    uploadErrorOccurred = true;
                    break;
                }
            }

            if (uploadErrorOccurred) {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Kirim Laporan';
                return;
            }

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memfinalisasi Laporan...';
            const finalFormData = new FormData(formPengaduan);
            finalFormData.append('upload_id', uploadId);

            uploadedFileTemporaryPaths.forEach(path => {
                finalFormData.append('uploaded_files[]', path);
            });

            finalFormData.delete('file_referensi');
            finalFormData.delete('bukti_pendukung[]');

            try {
                const finalResponse = await fetch(storeLaporanRoute, {
                    method: 'POST',
                    body: finalFormData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const finalResult = await finalResponse.json();

                if (!finalResponse.ok || !finalResult.success) {
                    let errorMessage = finalResult.message || 'Gagal menyimpan laporan.';
                    if (finalResult.errors) {
                        errorMessage += '<ul>';
                        for (const key in finalResult.errors) {
                            errorMessage += `<li>${finalResult.errors[key].join(', ')}</li>`;
                        }
                        errorMessage += '</ul>';
                    }
                    throw new Error(errorMessage);
                }
                if (formMessageDiv) formMessageDiv.innerHTML = `<div class="alert alert-success">${finalResult.message}</div>`;

                resetUIAfterSuccess();

                setTimeout(() => {
                    if (formMessageDiv) formMessageDiv.innerHTML = '';
                    window.location.href = '/';
                }, 2000);

            } catch (error) {
                if (formMessageDiv) formMessageDiv.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Kirim Laporan';
            }
        });
    } else {
        console.error("Form 'formPengaduan' atau tombol submit tidak ditemukan. Logika submit AJAX tidak akan berfungsi.");
    }
}
